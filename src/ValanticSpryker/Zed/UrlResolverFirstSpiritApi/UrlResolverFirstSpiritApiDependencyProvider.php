<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\UrlResolverFirstSpiritApi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \ValanticSpryker\Zed\UrlResolverFirstSpiritApi\UrlResolverFirstSpiritApiConfig getConfig()
 */
class UrlResolverFirstSpiritApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PRODUCT_STORAGE_CLIENT = 'PRODUCT_STORAGE_CLIENT';

    /**
     * @var string
     */
    public const CATEGORY_STORAGE_CLIENT = 'CATEGORY_STORAGE_CLIENT';

    /**
     * @var string
     */
    public const STORE_CLIENT = 'STORE_CLIENT';

    /**
     * @var string
     */
    public const CMS_STORAGE_CLIENT = 'CMS_STORAGE_CLIENT';

    /**
     * @var string
     */
    public const URL_STORAGE_CLIENT = 'URL_CLIENT';

    /**
     * @var string
     */
    public const LOCALE_FACADE = 'LOCALE_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->getProductStorageClient($container);
        $container = $this->getCategoryStorageClient($container);
        $container = $this->getStoreClient($container);
        $container = $this->getCmsStorageClient($container);
        $container = $this->getUrlClient($container);
        $container = $this->getLocaleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function getProductStorageClient(Container $container): Container
    {
        $container->set(self::PRODUCT_STORAGE_CLIENT, function (Container $container) {
            return $container->getLocator()->productStorage()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function getCategoryStorageClient(Container $container): Container
    {
        $container->set(self::CATEGORY_STORAGE_CLIENT, function (Container $container) {
            return $container->getLocator()->categoryStorage()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function getStoreClient(Container $container): Container
    {
        $container->set(self::STORE_CLIENT, function (Container $container) {
            return $container->getLocator()->store()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function getCmsStorageClient(Container $container): Container
    {
        $container->set(self::CMS_STORAGE_CLIENT, function (Container $container) {
            return $container->getLocator()->cmsStorage()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function getUrlClient(Container $container): Container
    {
        $container->set(self::URL_STORAGE_CLIENT, function (Container $container) {
            return $container->getLocator()->urlStorage()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function getLocaleFacade(Container $container): Container
    {
        $container->set(self::LOCALE_FACADE, function (Container $container) {
            return $container->getLocator()->locale()->facade();
        });

        return $container;
    }
}
