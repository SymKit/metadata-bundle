<?php

declare(strict_types=1);

namespace Symkit\MetadataBundle\Builder;

use Symfony\Contracts\Service\ResetInterface;
use Symkit\MetadataBundle\Contract\PageContextBuilderInterface;
use Symkit\MetadataBundle\Contract\PageContextProviderInterface;
use Symkit\MetadataBundle\Enum\OgType;
use Symkit\MetadataBundle\Enum\TwitterCard;
use Symkit\MetadataBundle\Model\PageContext;

final class PageContextBuilder implements PageContextBuilderInterface, PageContextProviderInterface, ResetInterface
{
    private ?string $title = null;
    private ?string $description = null;
    private ?string $ogImage = null;
    private OgType $ogType = OgType::WEBSITE;
    private TwitterCard $twitterCard = TwitterCard::SUMMARY_LARGE_IMAGE;
    private string $canonicalUrl = '';
    /** @var array<string, mixed> */
    private array $properties = [];
    private ?PageContext $context = null;

    public function setTitle(?string $title): self
    {
        $this->title = $title;
        $this->context = null;

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        $this->context = null;

        return $this;
    }

    public function setOgImage(?string $ogImage): self
    {
        $this->ogImage = $ogImage;
        $this->context = null;

        return $this;
    }

    public function setOgType(OgType $ogType): self
    {
        $this->ogType = $ogType;
        $this->context = null;

        return $this;
    }

    public function setTwitterCard(TwitterCard $twitterCard): self
    {
        $this->twitterCard = $twitterCard;
        $this->context = null;

        return $this;
    }

    public function setCanonicalUrl(string $canonicalUrl): self
    {
        $this->canonicalUrl = $canonicalUrl;
        $this->context = null;

        return $this;
    }

    public function setProperties(array $properties): self
    {
        $this->properties = $properties;
        $this->context = null;

        return $this;
    }

    public function addProperties(array $properties): self
    {
        $this->properties = array_merge($this->properties, $properties);
        $this->context = null;

        return $this;
    }

    public function build(): PageContext
    {
        return new PageContext(
            title: $this->title ?? '',
            description: $this->description ?? '',
            ogImage: $this->ogImage,
            ogType: $this->ogType,
            twitterCard: $this->twitterCard,
            canonicalUrl: $this->canonicalUrl,
            properties: $this->properties,
        );
    }

    public function getContext(): PageContext
    {
        return $this->context ??= $this->build();
    }

    public function reset(): void
    {
        $this->title = null;
        $this->description = null;
        $this->ogImage = null;
        $this->ogType = OgType::WEBSITE;
        $this->twitterCard = TwitterCard::SUMMARY_LARGE_IMAGE;
        $this->canonicalUrl = '';
        $this->properties = [];
        $this->context = null;
    }
}
