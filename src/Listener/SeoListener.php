<?php

declare(strict_types=1);

namespace Symkit\MetadataBundle\Listener;

use ReflectionMethod;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symkit\MetadataBundle\Attribute\Seo;
use Symkit\MetadataBundle\Contract\PageContextBuilderInterface;

#[AsEventListener(event: KernelEvents::CONTROLLER, method: 'onKernelController')]
final readonly class SeoListener
{
    public function __construct(
        private PageContextBuilderInterface $builder,
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

        if (null !== $attribute->title) {
            $this->builder->setTitle($attribute->title);
        }

        if (null !== $attribute->description) {
            $this->builder->setDescription($attribute->description);
        }

        if (null !== $attribute->ogImage) {
            $this->builder->setOgImage($attribute->ogImage);
        }

        $this->builder->setOgType($attribute->ogType);
        $this->builder->setTwitterCard($attribute->twitterCard);

        if ([] !== $attribute->properties) {
            $this->builder->addProperties($attribute->properties);
        }
    }

    private function resolveAttribute(ControllerEvent $event): ?Seo
    {
        $controller = $event->getController();

        if (\is_array($controller) && \is_object($controller[0]) && \is_string($controller[1])) {
            $reflection = new ReflectionMethod($controller[0], $controller[1]);
        } elseif (\is_object($controller) && method_exists($controller, '__invoke')) {
            $reflection = new ReflectionMethod($controller, '__invoke');
        } else {
            return null;
        }

        $attributes = $reflection->getAttributes(Seo::class);

        if ([] !== $attributes) {
            return $attributes[0]->newInstance();
        }

        $classAttributes = $reflection->getDeclaringClass()->getAttributes(Seo::class);

        if ([] !== $classAttributes) {
            return $classAttributes[0]->newInstance();
        }

        return null;
    }
}
