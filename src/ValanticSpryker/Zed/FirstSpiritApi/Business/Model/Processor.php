<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Generated\Shared\Transfer\FirstSpiritApiResponseTransfer;

class Processor implements ProcessorInterface
{
    /**
     * @var array<\ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface>
     */
    protected $preProcessStack;

    /**
     * @var array<\ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Post\PostProcessorInterface>
     */
    protected $postProcessStack;

    /**
     * @param array<\ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface> $preProcessStack
     * @param array<\ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Post\PostProcessorInterface> $postProcessStack
     */
    public function __construct(array $preProcessStack, array $postProcessStack)
    {
        $this->preProcessStack = $preProcessStack;
        $this->postProcessStack = $postProcessStack;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer
     */
    public function preProcess(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiRequestTransfer
    {
        foreach ($this->preProcessStack as $preProcessor) {
            $apiRequestTransfer = $preProcessor->process($apiRequestTransfer);
        }

        return $apiRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer
     */
    public function postProcess(FirstSpiritApiRequestTransfer $apiRequestTransfer, FirstSpiritApiResponseTransfer $apiResponseTransfer): FirstSpiritApiResponseTransfer
    {
        foreach ($this->postProcessStack as $postProcessor) {
            $apiResponseTransfer = $postProcessor->process($apiRequestTransfer, $apiResponseTransfer);
        }

        return $apiResponseTransfer;
    }
}
