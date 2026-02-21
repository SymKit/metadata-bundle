<?php

declare(strict_types=1);

namespace Symkit\MetadataBundle\Contract;

interface MetadataPopulatorInterface
{
    public function supports(object $subject): bool;

    public function populateMetadata(object $subject, PageContextBuilderInterface $builder): void;
}
