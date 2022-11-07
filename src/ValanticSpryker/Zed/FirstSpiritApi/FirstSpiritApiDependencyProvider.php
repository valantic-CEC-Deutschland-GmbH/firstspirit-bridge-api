<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi;

use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use ValanticSpryker\Zed\CategoryFirstSpiritApi\Communication\Plugin\CategoriesFirstSpiritApiResourcePlugin;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Communication\Plugin\CmsFirstSpiritApiResourcePlugin;
use ValanticSpryker\Zed\ProductFirstSpiritApi\Communication\Plugin\ProductsFirstSpiritApiResourcePlugin;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Communication\Plugin\LookUpFirstSpiritApiResourcePlugin;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Communication\Plugin\StoreFrontFirstSpiritApiResourcePlugin;

/**
 * @method \ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig getConfig()
 */
class FirstSpiritApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const PLUGINS_FIRST_SPIRIT_API_VALIDATOR = 'PLUGINS_FIRST_SPIRIT_API_VALIDATOR';
    public const PLUGINS_FIRST_SPIRIT_API_RESOURCE = 'PLUGINS_FIRST_SPIRIT_API_RESOURCES';
    public const PLUGINS_FIRST_SPIRIT_API_REQUEST_TRANSFER_FILTER = 'PLUGINS_FIRST_SPIRIT_API_REQUEST_TRANSFER_FILTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addApiValidatorPlugins($container);
        $container = $this->addApiResourcePlugins($container);
        $container = $this->addApiRequestFilterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container): UtilEncodingServiceInterface {
            return $container->getLocator()->utilEncoding()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addApiValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_FIRST_SPIRIT_API_VALIDATOR, function () {
            return $this->getApiValidatorPluginCollection();
        });

        return $container;
    }

    /**
     * @return array<\ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiValidatorPluginInterface>
     */
    private function getApiValidatorPluginCollection(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addApiResourcePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_FIRST_SPIRIT_API_RESOURCE, function () {
            return $this->getApiResourcePluginCollection();
        });

        return $container;
    }

    /**
     * @return array<\ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiResourcePluginInterface>
     */
    private function getApiResourcePluginCollection(): array
    {
        return [
            new CategoriesFirstSpiritApiResourcePlugin(),
            new CmsFirstSpiritApiResourcePlugin(),
            new ProductsFirstSpiritApiResourcePlugin(),
            new LookUpFirstSpiritApiResourcePlugin(),
            new StoreFrontFirstSpiritApiResourcePlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addApiRequestFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_FIRST_SPIRIT_API_REQUEST_TRANSFER_FILTER, function () {
            return $this->getApiRequestTransferFilterPluginCollection();
        });

        return $container;
    }

    /**
     * @return array<\ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiRequestTransferFilterPluginInterface>
     */
    private function getApiRequestTransferFilterPluginCollection(): array
    {
        return [];
    }
}
