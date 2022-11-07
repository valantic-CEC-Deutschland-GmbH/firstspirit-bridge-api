<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\Action;

use Generated\Shared\Transfer\FirstSpiritApiDataTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;

class UpdateActionPreProcessor implements PreProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function process(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiRequestTransfer
    {
        $method = $apiRequestTransfer->getResourceAction();
        if ($method !== FirstSpiritApiConfig::ACTION_UPDATE) {
            return $apiRequestTransfer;
        }

        $postData = (array)$apiRequestTransfer->getRequestData();
        $queryData = (array)$apiRequestTransfer->getQueryData();

        $idResource = $apiRequestTransfer->getPath();
        $apiDataTransfer = new FirstSpiritApiDataTransfer();
        $apiDataTransfer->setData($postData);
        $apiDataTransfer->setQueryData($queryData);

        $params = [
            $idResource,
            $apiDataTransfer,
        ];

        $apiRequestTransfer->setResourceParameters($params);

        return $apiRequestTransfer;
    }
}
