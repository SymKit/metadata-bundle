<?php

declare(strict_types=1);

namespace Symkit\MetadataBundle\Contract;

use Spatie\SchemaOrg\BaseType;

interface SchemaDefinitionInterface
{
    public function toSchemaOrg(): BaseType;
}
