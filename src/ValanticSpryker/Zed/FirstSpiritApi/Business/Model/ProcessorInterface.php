<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Generated\Shared\Transfer\FirstSpiritApiResponseTransfer;

interface ProcessorInterface
{
 /**
  * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
  * @param \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer $apiResponseTransfer
  *
  * @return \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer
  */
    public function postProcess(
        FirstSpiritApiRequestTransfer $apiRequestTransfer,
        FirstSpiritApiResponseTransfer $apiResponseTransfer
    ): FirstSpiritApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer
     */
    public function preProcess(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiRequestTransfer;
}
