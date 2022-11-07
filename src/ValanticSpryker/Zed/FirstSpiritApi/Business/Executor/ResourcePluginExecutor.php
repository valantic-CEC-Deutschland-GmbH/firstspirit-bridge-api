<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Executor;

use Generated\Shared\Transfer\FirstSpiritApiOptionsTransfer;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Exception\FirstSpiritApiDispatchingException;
use ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiResourcePluginInterface;
use ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\OptionsForCollectionInterface;
use ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\OptionsForItemInterface;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;

class ResourcePluginExecutor implements ResourcePluginExecutorInterface
{
    private array $apiResourcePlugins;

    /**
     * @var \ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig
     */
    private FirstSpiritApiConfig $apiConfig;

    /**
     * @param array $apiResourcePlugins
     * @param \ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig $apiConfig
     */
    public function __construct(
        array $apiResourcePlugins,
        FirstSpiritApiConfig $apiConfig
    ) {
        $this->apiResourcePlugins = $apiResourcePlugins;
        $this->apiConfig = $apiConfig;
    }

    /**
     * @param string $resource
     * @param string $method
     * @param int|null $id
     * @param array $params
     *
     * @throws \ValanticSpryker\Zed\FirstSpiritApi\Business\Exception\FirstSpiritApiDispatchingException
     *
     * @return mixed
     */
    public function execute(string $resource, string $method, ?int $id, array $params): mixed
    {
        foreach ($this->apiResourcePlugins as $apiResourcePlugin) {
            if (mb_strtolower($apiResourcePlugin->getResourceName()) !== mb_strtolower($resource)) {
                continue;
            }

            if ($method === FirstSpiritApiConfig::ACTION_OPTIONS) {
                return $this->getOptions($apiResourcePlugin, $id, $params);
            }

            /** @var callable $callable */
            $callable = [$apiResourcePlugin, $method];
            if (!is_callable($callable)) {
                throw new FirstSpiritApiDispatchingException($this->createUnsupportedMethodErrorMessage($method, $resource));
            }

            /** @var \Generated\Shared\Transfer\FirstSpiritApiItemTransfer|\Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer $responseTransfer */
            $responseTransfer = call_user_func_array($callable, $params);
            $apiOptionsTransfer = $this->getOptions($apiResourcePlugin, $id, $params);
            $responseTransfer->setOptions($apiOptionsTransfer->getOptions());

            return $responseTransfer;
        }

        throw new FirstSpiritApiDispatchingException($this->createUnsupportedMethodErrorMessage($method, $resource));
    }

    /**
     * @param string $method
     * @param string $resource
     *
     * @return string
     */
    private function createUnsupportedMethodErrorMessage(string $method, string $resource): string
    {
        return sprintf('Unsupported method "%s" for resource "%s"', $method, $resource);
    }

    /**
     * @param \ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiResourcePluginInterface $plugin
     * @param int|null $resourceId
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiOptionsTransfer
     */
    private function getOptions(FirstSpiritApiResourcePluginInterface $plugin, ?int $resourceId, array $params): FirstSpiritApiOptionsTransfer
    {
        if ($resourceId) {
            $options = $this->getOptionsForItem($plugin, $params);
        } else {
            $options = $this->getOptionsForCollection($plugin, $params);
        }

        $apiOptionsTransfer = new FirstSpiritApiOptionsTransfer();
        $apiOptionsTransfer->setOptions($options);

        return $apiOptionsTransfer;
    }

    /**
     * @param \ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiResourcePluginInterface $plugin
     * @param array $params
     *
     * @return array
     */
    private function getOptionsForItem(FirstSpiritApiResourcePluginInterface $plugin, array $params): array
    {
        if ($plugin instanceof OptionsForItemInterface) {
            $options = $plugin->getHttpMethodsForItem($params);
        } else {
            $options = $this->apiConfig->getHttpMethodsForItem();
        }

        if (!in_array(FirstSpiritApiConfig::HTTP_METHOD_OPTIONS, $options)) {
            $options[] = FirstSpiritApiConfig::HTTP_METHOD_OPTIONS;
        }

        return $options;
    }

    /**
     * @param \ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiResourcePluginInterface $plugin
     * @param array $params
     *
     * @return array<string>
     */
    private function getOptionsForCollection(FirstSpiritApiResourcePluginInterface $plugin, array $params): array
    {
        if ($plugin instanceof OptionsForCollectionInterface) {
            $options = $plugin->getHttpMethodsForCollection($params);
        } else {
            $options = $this->apiConfig->getHttpMethodsForCollection();
        }

        if (!in_array(FirstSpiritApiConfig::HTTP_METHOD_OPTIONS, $options)) {
            $options[] = FirstSpiritApiConfig::HTTP_METHOD_OPTIONS;
        }

        return $options;
    }
}
