<?php

declare(strict_types=1);

namespace Symkit\MetadataBundle\Contract;

use Symkit\MetadataBundle\Model\PageContext;

interface PageContextProviderInterface
{
    public function getContext(): PageContext;
}
