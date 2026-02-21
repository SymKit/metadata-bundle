<?php

declare(strict_types=1);

namespace Symkit\MetadataBundle\Contract;

use Symkit\MetadataBundle\Model\BreadcrumbItem;

interface BreadcrumbServiceInterface
{
    public function initialize(string $context = 'website'): void;

    public function isRootRoute(string $route, string $context = 'website'): bool;

    public function add(string $name, string $url): self;

    /** @return list<BreadcrumbItem> */
    public function getItems(): array;
}
