<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business;

use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;

interface UrlResolverFirstSpiritApiFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $firstSpiritApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getIdentifierByTypeAndId(FirstSpiritApiRequestTransfer $firstSpiritApiRequestTransfer): FirstSpiritApiCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $firstSpiritApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getIdentifierByUrl(FirstSpiritApiRequestTransfer $firstSpiritApiRequestTransfer): FirstSpiritApiCollectionTransfer;
}
