<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\CategoryStorage;

use Spryker\Client\CategoryStorage\CategoryStorageDependencyProvider;
use Spryker\Client\CategoryStorage\CategoryStorageFactory as SprykerCategoryStorageFactory;
use Spryker\Client\CategoryStorage\Storage\CategoryNodeStorageInterface;
use ValanticSpryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageInterface;
use ValanticSpryker\Client\CategoryStorage\Storage\CategoryNodeStorage;

class CategoryStorageFactory extends SprykerCategoryStorageFactory
{
    /**
     * @return \ValanticSpryker\Client\CategoryStorage\Storage\CategoryNodeStorageInterface
     */
    public function createCategoryNodeStorage(): CategoryNodeStorageInterface
    {
        return new CategoryNodeStorage(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
        );
    }

    /**
     * @return \ValanticSpryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageInterface
     */
    protected function getStorageClient(): CategoryStorageToStorageInterface
    {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::CLIENT_STORAGE);
    }
}
