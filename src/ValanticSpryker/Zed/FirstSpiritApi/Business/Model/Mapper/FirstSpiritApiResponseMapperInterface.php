<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiItemTransfer;
use Generated\Shared\Transfer\FirstSpiritApiResponseTransfer;

interface FirstSpiritApiResponseMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer $apiCollectionTransfer
     * @param \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer
     */
    public function mapApiCollectionTransferToApiResponseTransfer(
        FirstSpiritApiCollectionTransfer $apiCollectionTransfer,
        FirstSpiritApiResponseTransfer $apiResponseTransfer
    ): FirstSpiritApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiItemTransfer $apiItemTransfer
     * @param \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer
     */
    public function mapApiItemTransferToApiResponseTransfer(
        FirstSpiritApiItemTransfer $apiItemTransfer,
        FirstSpiritApiResponseTransfer $apiResponseTransfer
    ): FirstSpiritApiResponseTransfer;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\FirstSpiritApiValidationErrorTransfer> $apiValidationErrorTransfers
     * @param \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer
     */
    public function mapValidationErrorTransfersToApiResponseTransfer(
        ArrayObject $apiValidationErrorTransfers,
        FirstSpiritApiResponseTransfer $apiResponseTransfer
    ): FirstSpiritApiResponseTransfer;
}
