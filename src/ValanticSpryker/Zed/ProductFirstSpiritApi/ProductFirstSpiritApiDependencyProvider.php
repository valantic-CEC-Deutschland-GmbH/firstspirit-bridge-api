<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductFirstSpiritApi;

use Spryker\Client\Catalog\CatalogClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface;

/**
 * @method \ValanticSpryker\Zed\ProductFirstSpiritApi\ProductFirstSpiritApiConfig getConfig()
 */
class ProductFirstSpiritApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PRODUCT_STORAGE_FACADE = 'PRODUCT_STORAGE_FACADE';
    public const PRODUCT_STORAGE_CLIENT = 'PRODUCT_STORAGE_CLIENT';
    public const CATALOG_CLIENT = 'CATALOG_CLIENT';
    public const LOCALE_FACADE = 'LOCALE_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $this->addProductStorageFacade($container);
        $this->addProductStorageClient($container);
        $this->addCatalogClient($container);
        $this->addLocaleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addProductStorageClient(Container $container): void
    {
        $container->set(
            self::PRODUCT_STORAGE_CLIENT,
            static function (Container $container): ProductStorageClientInterface {
                return $container->getLocator()->productStorage()->client();
            },
        );
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addProductStorageFacade(Container $container): void
    {
        $container->set(
            self::PRODUCT_STORAGE_FACADE,
            static function (Container $container): ProductStorageFacadeInterface {
                return $container->getLocator()->productStorage()->facade();
            },
        );
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addCatalogClient(Container $container): void
    {
        $container->set(
            self::CATALOG_CLIENT,
            static function (Container $container): CatalogClientInterface {
                return $container->getLocator()->catalog()->client();
            },
        );
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addLocaleFacade(Container $container): void
    {
        $container->set(
            self::LOCALE_FACADE,
            static function (Container $container): LocaleFacadeInterface {
                return $container->getLocator()->locale()->facade();
            },
        );
    }
}
