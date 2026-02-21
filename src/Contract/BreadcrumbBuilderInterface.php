<?php

declare(strict_types=1);

namespace Symkit\MetadataBundle\Contract;

interface BreadcrumbBuilderInterface
{
    public function build(BreadcrumbServiceInterface $service): void;

    public function isRootRoute(string $route): bool;
}
