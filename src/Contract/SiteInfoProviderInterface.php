<?php

declare(strict_types=1);

namespace Symkit\MetadataBundle\Contract;

interface SiteInfoProviderInterface
{
    public function getWebsiteName(): string;

    public function getWebsiteDescription(): ?string;

    public function getDefaultOgImage(): ?string;

    public function getFavicon(): ?string;

    public function getAppleTouchIcon(): ?string;

    public function getAndroidIcon192(): ?string;

    public function getAndroidIcon512(): ?string;
}
