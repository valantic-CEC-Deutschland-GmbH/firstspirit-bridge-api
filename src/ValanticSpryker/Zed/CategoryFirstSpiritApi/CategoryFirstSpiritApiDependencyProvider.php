<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CategoryFirstSpiritApi;

use Spryker\Client\CategoryStorage\CategoryStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use ValanticSpryker\Client\FirstSpiritApi\FirstSpiritApiClientInterface;

/**
 * @method \ValanticSpryker\Zed\CategoryFirstSpiritApi\CategoryFirstSpiritApiConfig getConfig()
 */
class CategoryFirstSpiritApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CATEGORY_STORAGE = 'CLIENT_CATEGORY_STORAGE';

    public const CLIENT_FIRST_SPIRIT_API = 'CLIENT_FIRST_SPIRIT_API';

    public const CLIENT_STORE = 'CLIENT_STORE';

    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addCategoryStorageClient($container);
        $container = $this->addFirstSpiritApiClient($container);
        $container = $this->addStoreClient($container);
        $container = $this->addLocaleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addCategoryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_CATEGORY_STORAGE, function (Container $container): CategoryStorageClientInterface {
            return $container->getLocator()->categoryStorage()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addFirstSpiritApiClient(Container $container): Container
    {
        $container->set(static::CLIENT_FIRST_SPIRIT_API, function (Container $container): FirstSpiritApiClientInterface {
            return $container->getLocator()->firstSpiritApi()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container): StoreClientInterface {
            return $container->getLocator()->store()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container): LocaleFacadeInterface {
            return $container->getLocator()->locale()->facade();
        });

        return $container;
    }
}
