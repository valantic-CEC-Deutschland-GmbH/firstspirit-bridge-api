<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CmsFirstSpiritApi\Business\ResponseHandler;

use Generated\Shared\Transfer\FirstSpiritApiItemTransfer;
use Symfony\Component\HttpFoundation\Response;

class ResourceResponseHandler implements ResourceResponseHandlerInterface
{
 /**
  * @param \Generated\Shared\Transfer\FirstSpiritApiItemTransfer $responseTransfer
  *
  * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
  */
    public function setResponseToBadTransfer(FirstSpiritApiItemTransfer $responseTransfer): FirstSpiritApiItemTransfer
    {
        return $responseTransfer->setStatusCode(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiItemTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function setResponseToInternalError(FirstSpiritApiItemTransfer $responseTransfer): FirstSpiritApiItemTransfer
    {
        return $responseTransfer->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiItemTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function setResponseToNotFound(FirstSpiritApiItemTransfer $responseTransfer): FirstSpiritApiItemTransfer
    {
        return $responseTransfer->setStatusCode(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiItemTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function setResponseToSuccessful(FirstSpiritApiItemTransfer $responseTransfer): FirstSpiritApiItemTransfer
    {
        return $responseTransfer->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiItemTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function setResponseCodeToUpdated(FirstSpiritApiItemTransfer $responseTransfer): FirstSpiritApiItemTransfer
    {
        return $responseTransfer->setStatusCode(Response::HTTP_CREATED);
    }
}
