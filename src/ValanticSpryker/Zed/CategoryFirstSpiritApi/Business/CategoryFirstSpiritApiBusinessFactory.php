<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CategoryFirstSpiritApi\Business;

use Spryker\Client\CategoryStorage\CategoryStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use ValanticSpryker\Client\FirstSpiritApi\FirstSpiritApiClientInterface;
use ValanticSpryker\Zed\CategoryFirstSpiritApi\Business\Model\Reader\CategoryFirstSpiritReader;
use ValanticSpryker\Zed\CategoryFirstSpiritApi\CategoryFirstSpiritApiDependencyProvider;

/**
 * @method \ValanticSpryker\Zed\CategoryFirstSpiritApi\CategoryFirstSpiritApiConfig getConfig()
 */
class CategoryFirstSpiritApiBusinessFactory extends AbstractBusinessFactory
{
 /**
  * @return \ValanticSpryker\Zed\CategoryFirstSpiritApi\Business\Model\Reader\CategoryFirstSpiritReader
  */
    public function createCategoryReader(): CategoryFirstSpiritReader
    {
        return new CategoryFirstSpiritReader(
            $this->getCategoryStorageClient(),
            $this->getLocaleFacade(),
            $this->getConfig(),
            $this->getStoreClient(),
            $this->getFirstSpiritApiClient(),
        );
    }

    /**
     * @return \Spryker\Client\CategoryStorage\CategoryStorageClientInterface
     */
    private function getCategoryStorageClient(): CategoryStorageClientInterface
    {
        return $this->getProvidedDependency(CategoryFirstSpiritApiDependencyProvider::CLIENT_CATEGORY_STORAGE);
    }

    /**
     * @return \Spryker\Client\Store\StoreClientInterface
     */
    private function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(CategoryFirstSpiritApiDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    private function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getProvidedDependency(CategoryFirstSpiritApiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \ValanticSpryker\Client\FirstSpiritApi\FirstSpiritApiClientInterface
     */
    private function getFirstSpiritApiClient(): FirstSpiritApiClientInterface
    {
        return $this->getProvidedDependency(CategoryFirstSpiritApiDependencyProvider::CLIENT_FIRST_SPIRIT_API);
    }
}
