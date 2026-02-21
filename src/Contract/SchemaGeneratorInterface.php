<?php

declare(strict_types=1);

namespace Symkit\MetadataBundle\Contract;

use Spatie\SchemaOrg\BaseType;

interface SchemaGeneratorInterface
{
    /** @return iterable<BaseType> */
    public function generate(): iterable;
}
