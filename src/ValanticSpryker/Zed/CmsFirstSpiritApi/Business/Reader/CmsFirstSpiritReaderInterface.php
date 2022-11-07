<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Reader;

use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;

interface CmsFirstSpiritReaderInterface
{
 /**
  * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
  *
  * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
  */
    public function getCmsPagesByIds(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer;
}
