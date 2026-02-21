<?php

declare(strict_types=1);

namespace Symkit\MetadataBundle\Contract;

use Symkit\MetadataBundle\Enum\OgType;
use Symkit\MetadataBundle\Enum\TwitterCard;
use Symkit\MetadataBundle\Model\PageContext;

interface PageContextBuilderInterface
{
    public function setTitle(?string $title): self;

    public function setDescription(?string $description): self;

    public function setOgImage(?string $ogImage): self;

    public function setOgType(OgType $ogType): self;

    public function setTwitterCard(TwitterCard $twitterCard): self;

    public function setCanonicalUrl(string $canonicalUrl): self;

    /**
     * @param array<string, mixed> $properties
     */
    public function setProperties(array $properties): self;

    /**
     * @param array<string, mixed> $properties
     */
    public function addProperties(array $properties): self;

    public function build(): PageContext;
}
