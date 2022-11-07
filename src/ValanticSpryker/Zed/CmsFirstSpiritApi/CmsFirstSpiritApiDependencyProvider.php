<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CmsFirstSpiritApi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \ValanticSpryker\Zed\CmsFirstSpiritApi\CmsFirstSpiritApiConfig getConfig()
 */
class CmsFirstSpiritApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CMS_FACADE = 'CMS_FACADE';

    /**
     * @var string
     */
    public const CMS_STORAGE_CLIENT = 'CMS_STORAGE_CLIENT';

    /**
     * @var string
     */
    public const STORE_CLIENT = 'STORE_CLIENT';

    /**
     * @var string
     */
    public const LOCALE_CLIENT = 'LOCALE_CLIENT';

    /**
     * @var string
     */
    public const STORAGE_CLIENT = 'STORAGE_CLIENT';

    /**
     * @var string
     */
    public const CMS_PAGE_SEARCH_CLIENT = 'CMS_PAGE_SEARCH_CLIENT';

    /**
     * @var string
     */
    public const UTIL_TEXT_SERVICE = 'UTIL_TEXT_SERVICE';

    /**
     * @var string
     */
    public const LOCALE_FACADE = 'LOCALE_FACADE';

    /**
     * @var string
     */
    public const STORE_FACADE = 'STORE_FACADE';

    /**
     * @var string
     */
    public const CMS_STORAGE_FACADE = 'CMS_STORAGE_FACADE';

    /**
     * @var string
     */
    public const CMS_PAGE_SEARCH_FACADE = 'CMS_PAGE_SEARCH_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->getCmsStorageClient($container);
        $container = $this->getCmsFacade($container);
        $container = $this->getLocaleClient($container);
        $container = $this->getLocaleFacade($container);
        $container = $this->getStoreClient($container);
        $container = $this->getStorageClient($container);
        $container = $this->getCmsPageSearchClient($container);
        $container = $this->getUtilTextService($container);
        $container = $this->getStoreFacade($container);
        $container = $this->getCmsStorageFacade($container);
        $container = $this->getCmsPageSearchFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getCmsFacade(Container $container): Container
    {
        $container->set(self::CMS_FACADE, function (Container $container) {
            return $container->getLocator()->cms()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getCmsPageSearchFacade(Container $container): Container
    {
        $container->set(self::CMS_PAGE_SEARCH_FACADE, function (Container $container) {
            return $container->getLocator()->cmsPageSearch()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getCmsStorageClient(Container $container): Container
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
    protected function getCmsStorageFacade(Container $container): Container
    {
        $container->set(self::CMS_STORAGE_FACADE, function (Container $container) {
            return $container->getLocator()->cmsStorage()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getStoreClient(Container $container): Container
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
    public function getLocaleClient(Container $container): Container
    {
        $container->set(self::LOCALE_CLIENT, function (Container $container) {
            return $container->getLocator()->locale()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function getLocaleFacade(Container $container): Container
    {
        $container->set(self::LOCALE_FACADE, function (Container $container) {
            return $container->getLocator()->locale()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function getStoreFacade(Container $container): Container
    {
        $container->set(self::STORE_FACADE, function (Container $container) {
            return $container->getLocator()->store()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function getStorageClient(Container $container): Container
    {
        $container->set(self::STORAGE_CLIENT, function (Container $container) {
            return $container->getLocator()->storage()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function getCmsPageSearchClient(Container $container): Container
    {
        $container->set(self::CMS_PAGE_SEARCH_CLIENT, function (Container $container) {
            return $container->getLocator()->cmsPageSearch()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function getUtilTextService(Container $container): Container
    {
        $container->set(self::UTIL_TEXT_SERVICE, function (Container $container) {
            return $container->getLocator()->utilText()->service();
        });

        return $container;
    }
}
