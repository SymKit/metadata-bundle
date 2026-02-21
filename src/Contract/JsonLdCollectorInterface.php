<?php

declare(strict_types=1);

namespace Symkit\MetadataBundle\Contract;

use Spatie\SchemaOrg\BaseType;

interface JsonLdCollectorInterface
{
    public function add(SchemaDefinitionInterface|BaseType $schema): self;

    /** @return list<BaseType> */
    public function getSchemas(): array;
}
