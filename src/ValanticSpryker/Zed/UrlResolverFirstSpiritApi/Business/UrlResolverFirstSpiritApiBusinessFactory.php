<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business;

use Spryker\Client\CategoryStorage\CategoryStorageClientInterface;
use Spryker\Client\CmsStorage\CmsStorageClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Client\UrlStorage\UrlStorageClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Reader\UrlResolverFirstSpiritReader;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Resolver\CategoryUrlResolver;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Resolver\CmsPageUrlResolver;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Resolver\ProductUrlResolver;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\UrlResolverFirstSpiritApiDependencyProvider;

/**
 * @method \ValanticSpryker\Zed\UrlResolverFirstSpiritApi\UrlResolverFirstSpiritApiConfig getConfig()
 */
class UrlResolverFirstSpiritApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Reader\UrlResolverFirstSpiritReader
     */
    public function createUrlResolverFirstSpiritReader(): UrlResolverFirstSpiritReader
    {
        return new UrlResolverFirstSpiritReader(
            $this->getProductStorageClient(),
            $this->getCategoryStorageClient(),
            $this->getStoreClient(),
            $this->getCmsStorageClient(),
            $this->getUrlStorageClient(),
            $this->getLocaleFacade(),
            $this->getUrlResolverAttributePlugins(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductStorageClientInterface
    {
        return $this->getProvidedDependency(UrlResolverFirstSpiritApiDependencyProvider::PRODUCT_STORAGE_CLIENT);
    }

    /**
     * @return \Spryker\Client\CategoryStorage\CategoryStorageClientInterface
     */
    public function getCategoryStorageClient(): CategoryStorageClientInterface
    {
        return $this->getProvidedDependency(UrlResolverFirstSpiritApiDependencyProvider::CATEGORY_STORAGE_CLIENT);
    }

    /**
     * @return \Spryker\Client\Store\StoreClientInterface
     */
    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(UrlResolverFirstSpiritApiDependencyProvider::STORE_CLIENT);
    }

    /**
     * @return \Spryker\Client\CmsStorage\CmsStorageClientInterface
     */
    public function getCmsStorageClient(): CmsStorageClientInterface
    {
        return $this->getProvidedDependency(UrlResolverFirstSpiritApiDependencyProvider::CMS_STORAGE_CLIENT);
    }

    /**
     * @return \Spryker\Client\UrlStorage\UrlStorageClientInterface
     */
    public function getUrlStorageClient(): UrlStorageClientInterface
    {
        return $this->getProvidedDependency(UrlResolverFirstSpiritApiDependencyProvider::URL_STORAGE_CLIENT);
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    public function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getProvidedDependency(UrlResolverFirstSpiritApiDependencyProvider::LOCALE_FACADE);
    }

    /**
     * @return \ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Resolver\ProductUrlResolver
     */
    public function createProductUrlResolver(): ProductUrlResolver
    {
        return new ProductUrlResolver();
    }

    /**
     * @return \ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Resolver\CmsPageUrlResolver
     */
    public function createCmsPageUrlResolver(): CmsPageUrlResolver
    {
        return new CmsPageUrlResolver();
    }

    /**
     * @return \ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Resolver\CategoryUrlResolver
     */
    public function createCategoryUrlResolver(): CategoryUrlResolver
    {
        return new CategoryUrlResolver(
            $this->getCategoryStorageClient(),
            $this->getStoreClient(),
        );
    }

    /**
     * @return array
     */
    public function getUrlResolverAttributePlugins(): array
    {
        return [
            $this->createProductUrlResolver(),
            $this->createCmsPageUrlResolver(),
            $this->createCategoryUrlResolver(),
        ];
    }
}
