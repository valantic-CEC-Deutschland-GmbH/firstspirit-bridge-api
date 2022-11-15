<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\FirstSpiritApi;

use Spryker\Client\CategoryStorage\Dependency\Service\CategoryStorageToSynchronizationServiceInterface;
use Spryker\Client\CategoryStorage\Storage\CategoryNodeStorageInterface;
use Spryker\Client\Kernel\AbstractFactory;
use ValanticSpryker\Client\FirstSpiritApi\Dependency\Client\CategoryStorageToStorageInterface;
use ValanticSpryker\Client\FirstSpiritApi\Storage\CategoryNodeStorage;

class FirstSpiritApiFactory extends AbstractFactory
{
    /**
     * @return \ValanticSpryker\Client\FirstSpiritApi\Storage\CategoryNodeStorageInterface
     */
    public function createCategoryNodeStorage(): CategoryNodeStorageInterface
    {
        return new CategoryNodeStorage(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
        );
    }

    /**
     * @return \ValanticSpryker\Client\FirstSpiritApi\Dependency\Client\CategoryStorageToStorageInterface
     */
    private function getStorageClient(): CategoryStorageToStorageInterface
    {
        return $this->getProvidedDependency(FirstSpiritApiDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\CategoryStorage\Dependency\Service\CategoryStorageToSynchronizationServiceInterface
     */
    private function getSynchronizationService(): CategoryStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(FirstSpiritApiDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
