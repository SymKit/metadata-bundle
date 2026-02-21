<?php

declare(strict_types=1);

namespace Symkit\MetadataBundle\Listener;

use ReflectionMethod;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symkit\MetadataBundle\Attribute\Breadcrumb;
use Symkit\MetadataBundle\Contract\BreadcrumbServiceInterface;

#[AsEventListener(event: KernelEvents::CONTROLLER, method: 'onKernelController')]
final readonly class BreadcrumbListener
{
    public function __construct(
        private BreadcrumbServiceInterface $breadcrumbService,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $attribute = $this->resolveAttribute($event);
        if (null === $attribute) {
            return;
        }

        $this->breadcrumbService->initialize($attribute->context);

        foreach ($attribute->items as $item) {
            $label = $item['label'];
            $url = $item['url'] ?? null;

            if (null === $url && isset($item['route'])) {
                $url = $this->urlGenerator->generate(
                    $item['route'],
                    $item['params'] ?? [],
                    UrlGeneratorInterface::ABSOLUTE_URL,
                );
            }

            if (null !== $url) {
                $this->breadcrumbService->add($label, $url);
            }
        }
    }

    private function resolveAttribute(ControllerEvent $event): ?Breadcrumb
    {
        $controller = $event->getController();

        if (\is_array($controller) && \is_object($controller[0]) && \is_string($controller[1])) {
            $reflection = new ReflectionMethod($controller[0], $controller[1]);
        } elseif (\is_object($controller) && method_exists($controller, '__invoke')) {
            $reflection = new ReflectionMethod($controller, '__invoke');
        } else {
            return null;
        }

        $attributes = $reflection->getAttributes(Breadcrumb::class);

        if ([] !== $attributes) {
            return $attributes[0]->newInstance();
        }

        $classAttributes = $reflection->getDeclaringClass()->getAttributes(Breadcrumb::class);

        if ([] !== $classAttributes) {
            return $classAttributes[0]->newInstance();
        }

        return null;
    }
}
