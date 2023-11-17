<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductFirstSpiritApi\Business;

use Spryker\Client\Catalog\CatalogClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use ValanticSpryker\Zed\ProductFirstSpiritApi\Business\Mapper\ProductFirstSpiritApiBusinessMapper;
use ValanticSpryker\Zed\ProductFirstSpiritApi\Business\Mapper\ProductFirstSpiritApiBusinessMapperInterface;
use ValanticSpryker\Zed\ProductFirstSpiritApi\Business\Reader\ProductReader;
use ValanticSpryker\Zed\ProductFirstSpiritApi\ProductFirstSpiritApiDependencyProvider;

/**
 * @method \ValanticSpryker\Zed\ProductFirstSpiritApi\ProductFirstSpiritApiConfig getConfig()
 */
class ProductFirstSpiritApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \ValanticSpryker\Zed\ProductFirstSpiritApi\Business\Reader\ProductReader
     */
    public function createProductReader(): ProductReader
    {
        return new ProductReader(
            $this->getProductStorageClient(),
            $this->getCatalogClient(),
            $this->getLocaleFacade(),
            $this->createProductFirstSpiritApiBusinessMapper(),
            $this->getConfig(),
            $this->getQueryExpanderPlugins(),
        );
    }

    /**
     * @return \ValanticSpryker\Zed\ProductFirstSpiritApi\Business\Mapper\ProductFirstSpiritApiBusinessMapperInterface
     */
    public function createProductFirstSpiritApiBusinessMapper(): ProductFirstSpiritApiBusinessMapperInterface
    {
        return new ProductFirstSpiritApiBusinessMapper($this->getProductStorageClient());
    }

    /**
     * @return \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductFirstSpiritApiDependencyProvider::PRODUCT_STORAGE_CLIENT);
    }

    /**
     * @return \Spryker\Client\Catalog\CatalogClientInterface
     */
    public function getCatalogClient(): CatalogClientInterface
    {
        return $this->getProvidedDependency(ProductFirstSpiritApiDependencyProvider::CATALOG_CLIENT);
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    public function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductFirstSpiritApiDependencyProvider::LOCALE_FACADE);
    }

    /**
     * @return array<\ValanticSpryker\Zed\ProductFirstSpiritApi\Communication\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    private function getQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductFirstSpiritApiDependencyProvider::PLUGINS_QUERY_EXPANDER);
    }
}
