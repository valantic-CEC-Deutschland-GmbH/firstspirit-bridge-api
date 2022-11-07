<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Filter;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;

interface FirstSpiritApiRequestTransferFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer
     */
    public function filter(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiRequestTransfer;
}
