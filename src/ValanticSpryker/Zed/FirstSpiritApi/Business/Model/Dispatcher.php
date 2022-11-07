<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiDataTransfer;
use Generated\Shared\Transfer\FirstSpiritApiItemTransfer;
use Generated\Shared\Transfer\FirstSpiritApiOptionsTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Generated\Shared\Transfer\FirstSpiritApiResponseTransfer;
use Throwable;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Executor\ResourcePluginExecutorInterface;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Mapper\FirstSpiritApiResponseMapperInterface;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Validator\FirstSpiritApiValidatorInterface;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;

class Dispatcher implements DispatcherInterface
{
 /**
  * @var \ValanticSpryker\Zed\FirstSpiritApi\Business\Executor\ResourcePluginExecutorInterface
  */
    private ResourcePluginExecutorInterface $resourcePluginExecutor;

    /**
     * @var \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\ProcessorInterface
     */
    private ProcessorInterface $processor;

    /**
     * @var \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Validator\FirstSpiritApiValidatorInterface
     */
    private FirstSpiritApiValidatorInterface $apiValidator;

    /**
     * @var \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Mapper\FirstSpiritApiResponseMapperInterface
     */
    private FirstSpiritApiResponseMapperInterface $apiResponseMapper;

    /**
     * @param \ValanticSpryker\Zed\FirstSpiritApi\Business\Executor\ResourcePluginExecutorInterface $resourcePluginExecutor
     * @param \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\ProcessorInterface $processor
     * @param \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Validator\FirstSpiritApiValidatorInterface $apiValidator
     * @param \ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Mapper\FirstSpiritApiResponseMapperInterface $apiResponseMapper
     */
    public function __construct(
        ResourcePluginExecutorInterface $resourcePluginExecutor,
        ProcessorInterface $processor,
        FirstSpiritApiValidatorInterface $apiValidator,
        FirstSpiritApiResponseMapperInterface $apiResponseMapper
    ) {
        $this->resourcePluginExecutor = $resourcePluginExecutor;
        $this->processor = $processor;
        $this->apiValidator = $apiValidator;
        $this->apiResponseMapper = $apiResponseMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer
     */
    public function dispatch(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiResponseTransfer
    {
        $apiRequestTransfer = $this->processor->preProcess($apiRequestTransfer);

        $apiResponseTransfer = $this->dispatchToResource($apiRequestTransfer);

        $apiResponseTransfer = $this->processor->postProcess($apiRequestTransfer, $apiResponseTransfer);

        if ($apiResponseTransfer->getCode() === null) {
            $apiResponseTransfer->setCode(FirstSpiritApiConfig::HTTP_CODE_SUCCESS);
        }

        return $apiResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer
     */
    protected function dispatchToResource(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiResponseTransfer
    {
        $apiResponseTransfer = new FirstSpiritApiResponseTransfer();

        try {
            $apiValidationErrorTransfers = $this->getValidationErrors($apiRequestTransfer);
            if ($apiValidationErrorTransfers !== []) {
                return $this->apiResponseMapper->mapValidationErrorTransfersToApiResponseTransfer(new ArrayObject($apiValidationErrorTransfers), $apiResponseTransfer);
            }

            $apiPluginCallResponseTransfer = $this->resourcePluginExecutor->execute(
                $apiRequestTransfer->getResourceOrFail(),
                $apiRequestTransfer->getResourceActionOrFail(),
                $apiRequestTransfer->getResourceId(),
                $apiRequestTransfer->getResourceParameters(),
            );
            $apiResponseTransfer->setType(get_class($apiPluginCallResponseTransfer));

            if ($apiPluginCallResponseTransfer instanceof FirstSpiritApiOptionsTransfer) {
                return $apiResponseTransfer->setOptions($apiPluginCallResponseTransfer->getOptions());
            }

            if ($apiPluginCallResponseTransfer instanceof FirstSpiritApiCollectionTransfer) {
                return $this->apiResponseMapper->mapApiCollectionTransferToApiResponseTransfer($apiPluginCallResponseTransfer, $apiResponseTransfer);
            }

            if ($apiPluginCallResponseTransfer instanceof FirstSpiritApiItemTransfer) {
                return $this->apiResponseMapper->mapApiItemTransferToApiResponseTransfer($apiPluginCallResponseTransfer, $apiResponseTransfer);
            }
        } catch (Throwable $e) {
            $apiResponseTransfer->setCode($this->resolveStatusCode((int)$e->getCode()));
            $apiResponseTransfer->setMessage($e->getMessage());
            $apiResponseTransfer->setStackTrace(get_class($e) . ' (' . $e->getFile() . ', line ' . $e->getLine() . '): ' . $e->getTraceAsString());
        }

        return $apiResponseTransfer;
    }

    /**
     * @param int $code
     *
     * @return int
     */
    protected function resolveStatusCode($code): int
    {
        if ($code < FirstSpiritApiConfig::HTTP_CODE_SUCCESS || $code > FirstSpiritApiConfig::HTTP_CODE_INTERNAL_ERROR) {
            return FirstSpiritApiConfig::HTTP_CODE_INTERNAL_ERROR;
        }

        return $code;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\FirstSpiritApiValidationErrorTransfer>
     */
    protected function getValidationErrors(FirstSpiritApiRequestTransfer $apiRequestTransfer): array
    {
        $resourceParameters = $apiRequestTransfer->getResourceParameters();

        $apiDataTransfer = null;
        foreach ($resourceParameters as $resourceParameter) {
            if (!$resourceParameter instanceof FirstSpiritApiDataTransfer) {
                continue;
            }

            $apiDataTransfer = $resourceParameter;

            break;
        }

        if ($apiDataTransfer === null) {
            return [];
        }

        $apiRequestTransfer->setFirstSpiritApiData($apiDataTransfer);

        return $this->apiValidator->validate($apiRequestTransfer);
    }
}
