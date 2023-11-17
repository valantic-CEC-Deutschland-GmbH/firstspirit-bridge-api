<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductFirstSpiritApi\Communication\Dependency\Plugin;

interface QueryExpanderPluginInterface
{
    /**
     * @param array $queryData
     *
     * @return array
     */
    public function expandQueryData(array $queryData): array;
}
