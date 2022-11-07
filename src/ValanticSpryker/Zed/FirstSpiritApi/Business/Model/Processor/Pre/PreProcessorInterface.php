<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;

interface PreProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer
     */
    public function process(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiRequestTransfer;
}
