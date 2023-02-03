<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\FirstSpiritApi\Dependency\Client;

use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageBridge as SprykerCategoryStorageToStorageBridge;

class CategoryStorageToStorageAdapter extends SprykerCategoryStorageToStorageBridge implements CategoryStorageToStorageInterface
{
    /**
     * @param string $key
     *
     * @return array<string>
     */
    public function getAllChildrenKeys(string $key): array
    {
        return $this->storageClient->getKeys($key . ':*');
    }
}
