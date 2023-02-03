<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiItemTransfer;
use Generated\Shared\Transfer\FirstSpiritApiMetaTransfer;
use Generated\Shared\Transfer\FirstSpiritApiResponseTransfer;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;

class FirstSpiritApiResponseMapper implements FirstSpiritApiResponseMapperInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_VALIDATION_ERRORS = 'Validation errors.';

    /**
     * @inheritDoc
     */
    public function mapApiItemTransferToApiResponseTransfer(
        FirstSpiritApiItemTransfer $apiItemTransfer,
        FirstSpiritApiResponseTransfer $apiResponseTransfer
    ): FirstSpiritApiResponseTransfer {
        if ($apiItemTransfer->getValidationErrors()->count()) {
            return $this->mapValidationErrorTransfersToApiResponseTransfer($apiItemTransfer->getValidationErrors(), $apiResponseTransfer);
        }

        if ($apiItemTransfer->getStatusCode() !== null) {
            $apiResponseTransfer->setCode($apiItemTransfer->getStatusCode());
        }

        $apiResponseTransfer->setMeta($this->mapApiItemTransferToApiMetaTransfer($apiItemTransfer, new FirstSpiritApiMetaTransfer()));

        return $apiResponseTransfer->fromArray($apiItemTransfer->toArray(), true);
    }

    /**
     * @inheritDoc
     */
    public function mapApiCollectionTransferToApiResponseTransfer(
        FirstSpiritApiCollectionTransfer $apiCollectionTransfer,
        FirstSpiritApiResponseTransfer $apiResponseTransfer
    ): FirstSpiritApiResponseTransfer {
        if ($apiCollectionTransfer->getValidationErrors()->count()) {
            return $this->mapValidationErrorTransfersToApiResponseTransfer($apiCollectionTransfer->getValidationErrors(), $apiResponseTransfer);
        }

        if ($apiCollectionTransfer->getStatusCode() !== null) {
            $apiResponseTransfer->setCode($apiCollectionTransfer->getStatusCode());
        }

        return $apiResponseTransfer->fromArray($apiCollectionTransfer->toArray(), true);
    }

    /**
     * @inheritDoc
     */
    public function mapValidationErrorTransfersToApiResponseTransfer(
        ArrayObject $apiValidationErrorTransfers,
        FirstSpiritApiResponseTransfer $apiResponseTransfer
    ): FirstSpiritApiResponseTransfer {
        return $apiResponseTransfer
            ->setCode(FirstSpiritApiConfig::HTTP_CODE_VALIDATION_ERRORS)
            ->setMessage(static::MESSAGE_VALIDATION_ERRORS)
            ->setValidationErrors($apiValidationErrorTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiItemTransfer $apiItemTransfer
     * @param \Generated\Shared\Transfer\FirstSpiritApiMetaTransfer $apiMetaTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiMetaTransfer
     */
    private function mapApiItemTransferToApiMetaTransfer(
        FirstSpiritApiItemTransfer $apiItemTransfer,
        FirstSpiritApiMetaTransfer $apiMetaTransfer
    ): FirstSpiritApiMetaTransfer {
        return $apiMetaTransfer->setResourceId($apiItemTransfer->getId());
    }
}
