<?php

declare(strict_types=1);

namespace Symkit\MetadataBundle\Contract;

interface JsonLdPopulatorInterface
{
    public function supports(object $subject): bool;

    public function populateJsonLd(object $subject, JsonLdCollectorInterface $collector): void;
}
