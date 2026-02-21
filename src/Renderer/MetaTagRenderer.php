<?php

declare(strict_types=1);

namespace Symkit\MetadataBundle\Renderer;

use Symkit\MetadataBundle\Contract\PageContextProviderInterface;
use Symkit\MetadataBundle\Contract\SiteInfoProviderInterface;

final readonly class MetaTagRenderer
{
    public function __construct(
        private PageContextProviderInterface $contextProvider,
        private SiteInfoProviderInterface $siteInfoProvider,
        private string $titleFormat = '{title} | {siteName}',
    ) {
    }

    public function render(): string
    {
        $context = $this->contextProvider->getContext();
        $tags = [];

        $formattedTitle = str_replace(
            ['{title}', '{siteName}'],
            [$context->title, $this->siteInfoProvider->getWebsiteName()],
            $this->titleFormat,
        );

        $tags[] = \sprintf('<title>%s</title>', $this->escape($formattedTitle));
        $tags[] = \sprintf('<meta name="description" content="%s">', $this->escape($context->description));

        if ('' !== $context->canonicalUrl) {
            $tags[] = \sprintf('<link rel="canonical" href="%s">', $this->escape($context->canonicalUrl));
        }

        $tags[] = \sprintf('<meta property="og:site_name" content="%s">', $this->escape($this->siteInfoProvider->getWebsiteName()));
        $tags[] = \sprintf('<meta property="og:type" content="%s">', $context->ogType->value);
        $tags[] = \sprintf('<meta property="og:title" content="%s">', $this->escape($context->title));
        $tags[] = \sprintf('<meta property="og:description" content="%s">', $this->escape($context->description));

        if ('' !== $context->canonicalUrl) {
            $tags[] = \sprintf('<meta property="og:url" content="%s">', $this->escape($context->canonicalUrl));
        }

        $ogImage = $context->ogImage ?? $this->siteInfoProvider->getDefaultOgImage();
        if (null !== $ogImage) {
            $tags[] = \sprintf('<meta property="og:image" content="%s">', $this->escape($ogImage));
        }

        $tags[] = \sprintf('<meta name="twitter:card" content="%s">', $context->twitterCard->value);
        $tags[] = \sprintf('<meta name="twitter:title" content="%s">', $this->escape($context->title));
        $tags[] = \sprintf('<meta name="twitter:description" content="%s">', $this->escape($context->description));

        if (null !== $ogImage) {
            $tags[] = \sprintf('<meta name="twitter:image" content="%s">', $this->escape($ogImage));
        }

        /** @var mixed $value */
        foreach ($context->properties as $name => $value) {
            $stringName = (string) $name;
            $stringValue = \is_scalar($value) ? (string) $value : '';

            if (str_starts_with($stringName, 'og:')) {
                $tags[] = \sprintf('<meta property="%s" content="%s">', $this->escape($stringName), $this->escape($stringValue));
            } else {
                $tags[] = \sprintf('<meta name="%s" content="%s">', $this->escape($stringName), $this->escape($stringValue));
            }
        }

        if ($favicon = $this->siteInfoProvider->getFavicon()) {
            $tags[] = \sprintf('<link rel="icon" type="image/x-icon" href="%s">', $this->escape($favicon));
        }

        if ($appleIcon = $this->siteInfoProvider->getAppleTouchIcon()) {
            $tags[] = \sprintf('<link rel="apple-touch-icon" sizes="180x180" href="%s">', $this->escape($appleIcon));
        }

        if ($android192 = $this->siteInfoProvider->getAndroidIcon192()) {
            $tags[] = \sprintf('<link rel="icon" type="image/png" sizes="192x192" href="%s">', $this->escape($android192));
        }

        if ($android512 = $this->siteInfoProvider->getAndroidIcon512()) {
            $tags[] = \sprintf('<link rel="icon" type="image/png" sizes="512x512" href="%s">', $this->escape($android512));
        }

        return implode("\n    ", $tags);
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8');
    }
}
