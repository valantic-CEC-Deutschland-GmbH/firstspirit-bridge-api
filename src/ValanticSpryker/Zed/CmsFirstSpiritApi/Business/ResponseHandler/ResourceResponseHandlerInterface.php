<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CmsFirstSpiritApi\Business\ResponseHandler;

use Generated\Shared\Transfer\FirstSpiritApiItemTransfer;

interface ResourceResponseHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiItemTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function setResponseToBadTransfer(FirstSpiritApiItemTransfer $responseTransfer): FirstSpiritApiItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiItemTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function setResponseToInternalError(FirstSpiritApiItemTransfer $responseTransfer): FirstSpiritApiItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiItemTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function setResponseToNotFound(FirstSpiritApiItemTransfer $responseTransfer): FirstSpiritApiItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiItemTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function setResponseToSuccessful(FirstSpiritApiItemTransfer $responseTransfer): FirstSpiritApiItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiItemTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function setResponseCodeToUpdated(FirstSpiritApiItemTransfer $responseTransfer): FirstSpiritApiItemTransfer;
}
