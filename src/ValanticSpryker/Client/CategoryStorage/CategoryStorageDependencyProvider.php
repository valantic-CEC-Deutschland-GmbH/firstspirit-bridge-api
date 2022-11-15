<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\CategoryStorage;

use Spryker\Client\CategoryStorage\CategoryStorageDependencyProvider as SprykerCategoryStorageDependencyProvider;
use Spryker\Client\Kernel\Container;
use ValanticSpryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageAdapter;

class CategoryStorageDependencyProvider extends SprykerCategoryStorageDependencyProvider
{
    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new CategoryStorageToStorageAdapter($container->getLocator()->storage()->client());
        });

        return $container;
    }
}
