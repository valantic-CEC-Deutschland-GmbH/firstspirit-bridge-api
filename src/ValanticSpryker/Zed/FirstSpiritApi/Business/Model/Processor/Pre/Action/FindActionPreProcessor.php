<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\Action;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;

class FindActionPreProcessor implements PreProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function process(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiRequestTransfer
    {
        $action = $apiRequestTransfer->getResourceAction();
        if ($action !== FirstSpiritApiConfig::ACTION_INDEX) {
            return $apiRequestTransfer;
        }

        $params = [$apiRequestTransfer];

        $apiRequestTransfer->setResourceParameters($params);

        return $apiRequestTransfer;
    }
}
