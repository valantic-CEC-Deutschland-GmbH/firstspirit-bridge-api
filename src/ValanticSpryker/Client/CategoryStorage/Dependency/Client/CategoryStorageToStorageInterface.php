<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\CategoryStorage\Dependency\Client;

use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageInterface as SprykerCategoryStorageToStorageInterface;

interface CategoryStorageToStorageInterface extends SprykerCategoryStorageToStorageInterface
{
    /**
     * @param string $key
     *
     * @return array<string>
     */
    public function getAllChildrenKeys(string $key): array;
}
