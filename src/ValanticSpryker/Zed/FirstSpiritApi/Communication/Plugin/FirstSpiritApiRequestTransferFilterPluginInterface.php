<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;

interface FirstSpiritApiRequestTransferFilterPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer
     */
    public function filter(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiRequestTransfer;
}
