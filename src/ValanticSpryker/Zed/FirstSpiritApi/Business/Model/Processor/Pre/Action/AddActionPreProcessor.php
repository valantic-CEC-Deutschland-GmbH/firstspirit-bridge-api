<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\Action;

use Generated\Shared\Transfer\FirstSpiritApiDataTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;

class AddActionPreProcessor implements PreProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function process(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiRequestTransfer
    {
        $action = $apiRequestTransfer->getResourceAction();
        if ($action !== FirstSpiritApiConfig::ACTION_CREATE) {
            return $apiRequestTransfer;
        }

        $queryData = (array)$apiRequestTransfer->getQueryData();

        $firstSpiritDataTransfer = new FirstSpiritApiDataTransfer();
        $firstSpiritDataTransfer->setData($apiRequestTransfer->getRequestData());
        $firstSpiritDataTransfer->setQueryData($queryData);

        $params = [
            $firstSpiritDataTransfer,
        ];

        $apiRequestTransfer->setResourceParameters($params);

        return $apiRequestTransfer;
    }
}
