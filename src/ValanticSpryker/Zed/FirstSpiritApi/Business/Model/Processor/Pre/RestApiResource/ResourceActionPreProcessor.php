<?php


declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\RestApiResource;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;

/**
 * @method \ValanticSpryker\Zed\FirstSpiritApi\Communication\FirstSpiritApiCommunicationFactory getFactory()
 * @method \ValanticSpryker\Zed\FirstSpiritApi\Business\FirstSpiritApiFacadeInterface getFacade()
 */
class ResourceActionPreProcessor implements PreProcessorInterface
{
    /**
     * Extracts the path segment responsible for building the resource action
     *
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer
     */
    public function process(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiRequestTransfer
    {
        $resourceId = $apiRequestTransfer->getResourceId();
        $requestType = $apiRequestTransfer->getRequestTypeOrFail();

        $resourceAction = null;
        if ($requestType === FirstSpiritApiConfig::HTTP_METHOD_OPTIONS) {
            $resourceAction = FirstSpiritApiConfig::ACTION_OPTIONS;
        } elseif (!$resourceId && $requestType === 'GET') {
            $resourceAction = FirstSpiritApiConfig::ACTION_INDEX;
        } elseif ($resourceId && $requestType === 'GET') {
            $resourceAction = FirstSpiritApiConfig::ACTION_READ;
        } elseif (!$resourceId && $requestType === 'POST') {
            $resourceAction = FirstSpiritApiConfig::ACTION_CREATE;
        } elseif ($resourceId && $requestType === 'PATCH') {
            $resourceAction = FirstSpiritApiConfig::ACTION_UPDATE;
        } elseif ($resourceId && $requestType === 'PUT') {
            $resourceAction = FirstSpiritApiConfig::ACTION_UPDATE;
        } elseif ($resourceId && $requestType === 'DELETE') {
            $resourceAction = FirstSpiritApiConfig::ACTION_DELETE;
        } elseif (!$resourceId && $requestType === 'HEAD') {
            $resourceAction = FirstSpiritApiConfig::ACTION_HEAD;
        }
        if ($resourceAction === null) {
            throw new BadRequestHttpException(sprintf('Request type %s does not fit to provided REST URI.', $requestType), null, ApiConfig::HTTP_CODE_NOT_ALLOWED);
        }

        $apiRequestTransfer->setResourceAction($resourceAction);

        return $apiRequestTransfer;
    }
}
