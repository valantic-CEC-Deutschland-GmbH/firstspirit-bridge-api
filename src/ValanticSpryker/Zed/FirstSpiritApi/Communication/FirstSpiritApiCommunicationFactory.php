<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Communication;

use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Api\Communication\Formatter\FormatterInterface;
use Spryker\Zed\Api\Communication\Formatter\JsonFormatter;
use Spryker\Zed\Api\Communication\Resolver\FormatterResolver;
use Spryker\Zed\Api\Communication\Resolver\FormatterResolverInterface;
use Spryker\Zed\Api\Dependency\Service\ApiToUtilEncodingServiceBridge;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use ValanticSpryker\Zed\FirstSpiritApi\Communication\EventListener\FirstSpiritApiControllerEventListener;
use ValanticSpryker\Zed\FirstSpiritApi\Communication\EventListener\FirstSpiritApiControllerEventListenerInterface;
use ValanticSpryker\Zed\FirstSpiritApi\Communication\Router\FirstSpiritApiRouter;
use ValanticSpryker\Zed\FirstSpiritApi\Communication\Transformer\Transformer;
use ValanticSpryker\Zed\FirstSpiritApi\Communication\Transformer\TransformerInterface;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiDependencyProvider;

/**
 * @method \ValanticSpryker\Zed\FirstSpiritApi\Business\FirstSpiritApiFacadeInterface getFacade()
 * @method \ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig getConfig()
 */
class FirstSpiritApiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Communication\EventListener\FirstSpiritApiControllerEventListenerInterface
     */
    public function createApiControllerEventListener(): FirstSpiritApiControllerEventListenerInterface
    {
        return new FirstSpiritApiControllerEventListener(
            $this->createTransformer(),
            $this->getFacade(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Communication\Transformer\Transformer
     */
    private function createTransformer(): TransformerInterface
    {
        return new Transformer(
            $this->createFormatterResolver(),
            $this->getConfig(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    private function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(FirstSpiritApiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\Api\Communication\Resolver\FormatterResolverInterface
     */
    private function createFormatterResolver(): FormatterResolverInterface
    {
        return new FormatterResolver([
                FormatterResolver::FORMATTER_TYPE_JSON => function () {
                    return $this->createJsonFormatter();
                },
            ]);
    }

    /**
     * @return \Spryker\Zed\Api\Communication\Formatter\FormatterInterface
     */
    public function createJsonFormatter(): FormatterInterface
    {
        return new JsonFormatter($this->createApiToUtilEncodingServiceBridge());
    }

    /**
     * @return \Spryker\Zed\Api\Dependency\Service\ApiToUtilEncodingServiceBridge
     */
    public function createApiToUtilEncodingServiceBridge(): ApiToUtilEncodingServiceBridge
    {
        return new ApiToUtilEncodingServiceBridge($this->getUtilEncodingService());
    }

    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Communication\Router\FirstSpiritApiRouter
     */
    public function createApiRouter(): FirstSpiritApiRouter
    {
        return new FirstSpiritApiRouter(
            $this->getConfig(),
        );
    }
}
