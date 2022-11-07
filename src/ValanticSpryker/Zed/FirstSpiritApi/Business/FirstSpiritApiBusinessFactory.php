<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Executor\ResourcePluginExecutor;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Executor\ResourcePluginExecutorInterface;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Dispatcher;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\DispatcherInterface;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Filter\FirstSpiritApiRequestTransferFilter;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Filter\FirstSpiritApiRequestTransferFilterInterface;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Mapper\FirstSpiritApiResponseMapper;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Mapper\FirstSpiritApiResponseMapperInterface;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\Action\AddActionPreProcessor;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\Action\FindActionPreProcessor;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\Action\HeadActionPreProcessor;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\Action\UpdateActionPreProcessor;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PathPreProcessor;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\RestApiResource\ResourceActionPreProcessor;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\RestApiResource\ResourceParametersPreProcessor;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\RestApiResource\ResourcePreProcessor;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\RestApiResource\ResourceQueryTypePreProcessor;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\ProcessorInterface;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Validator\FirstSpiritApiValidator;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Validator\FirstSpiritApiValidatorInterface;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiDependencyProvider;

/**
 * @method \ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig getConfig()
 */
class FirstSpiritApiBusinessFactory extends AbstractBusinessFactory
{
        /**
         * @return \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\DispatcherInterface
         */
    public function createDispatcher(): DispatcherInterface
    {
        return new Dispatcher(
            $this->createResourcePluginExecutor(),
            $this->createProcessor(),
            $this->createValidator(),
            $this->createApiResponseMapper(),
        );
    }

    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Business\Executor\ResourcePluginExecutorInterface
     */
    private function createResourcePluginExecutor(): ResourcePluginExecutorInterface
    {
        return new ResourcePluginExecutor(
            $this->getApiPlugins(),
            $this->getConfig(),
        );
    }

    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\ProcessorInterface
     */
    private function createProcessor(): ProcessorInterface
    {
        return new Processor(
            $this->getPreProcessorStack(),
            $this->getPostProcessorStack(),
        );
    }

    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Validator\FirstSpiritApiValidatorInterface
     */
    private function createValidator(): FirstSpiritApiValidatorInterface
    {
        return new FirstSpiritApiValidator(
            $this->getApiValidatorPlugins(),
        );
    }

    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Mapper\FirstSpiritApiResponseMapperInterface
     */
    private function createApiResponseMapper(): FirstSpiritApiResponseMapperInterface
    {
        return new FirstSpiritApiResponseMapper();
    }

    /**
     * @return array<\ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiValidatorPluginInterface>
     */
    private function getApiValidatorPlugins(): array
    {
        return $this->getProvidedDependency(FirstSpiritApiDependencyProvider::PLUGINS_FIRST_SPIRIT_API_VALIDATOR);
    }

    /**
     * @return array<\ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiResourcePluginInterface>
     */
    private function getApiPlugins(): array
    {
        return $this->getProvidedDependency(FirstSpiritApiDependencyProvider::PLUGINS_FIRST_SPIRIT_API_RESOURCE);
    }

    /**
     * @return array<\ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface>
     */
    private function getPreProcessorStack(): array
    {
        return [
            $this->createPathPreProcessor(),
            $this->createResourcePreProcessor(),
            $this->createResourceIdPreProcessor(),
            $this->createResourceActionPreProcessor(),
            $this->createResourceParametersPreProcessor(),
            $this->createFindActionPreProcessor(),
            $this->createHeadActionPreProcessor(),
            $this->createAddActionPreProcessor(),
            $this->createUpdateActionPreProcessor(),
        ];
    }

    /**
     * @return array<\ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Post\PostProcessorInterface>
     */
    private function getPostProcessorStack(): array
    {
        return [];
    }

    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface
     */
    private function createPathPreProcessor(): PreProcessorInterface
    {
        return new PathPreProcessor();
    }

    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface
     */
    private function createResourcePreProcessor(): PreProcessorInterface
    {
        return new ResourcePreProcessor();
    }

    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface
     */
    private function createResourceIdPreProcessor(): PreProcessorInterface
    {
        return new ResourceQueryTypePreProcessor();
    }

    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface
     */
    private function createResourceActionPreProcessor(): PreProcessorInterface
    {
        return new ResourceActionPreProcessor();
    }

    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface
     */
    private function createResourceParametersPreProcessor(): PreProcessorInterface
    {
        return new ResourceParametersPreProcessor();
    }

    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface
     */
    private function createFindActionPreProcessor(): PreProcessorInterface
    {
        return new FindActionPreProcessor();
    }

    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface
     */
    private function createHeadActionPreProcessor(): PreProcessorInterface
    {
        return new HeadActionPreProcessor();
    }

    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Filter\FirstSpiritApiRequestTransferFilterInterface
     */
    public function createRequestTransferFilter(): FirstSpiritApiRequestTransferFilterInterface
    {
        return new FirstSpiritApiRequestTransferFilter($this->getApiRequestTransferFilterPlugins());
    }

    /**
     * @return array<\ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiRequestTransferFilterPluginInterface>
     */
    public function getApiRequestTransferFilterPlugins(): array
    {
        return $this->getProvidedDependency(FirstSpiritApiDependencyProvider::PLUGINS_FIRST_SPIRIT_API_REQUEST_TRANSFER_FILTER);
    }

    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface
     */
    private function createAddActionPreProcessor(): PreProcessorInterface
    {
        return new AddActionPreProcessor();
    }

    /**
     * @return \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface
     */
    private function createUpdateActionPreProcessor(): PreProcessorInterface
    {
        return new UpdateActionPreProcessor();
    }
}
