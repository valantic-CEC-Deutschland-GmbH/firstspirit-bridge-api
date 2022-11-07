<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business;

use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\UrlResolverFirstSpiritApiBusinessFactory getFactory()
 */
class UrlResolverFirstSpiritApiFacade extends AbstractFacade implements UrlResolverFirstSpiritApiFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $firstSpiritApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getIdentifierByTypeAndId(FirstSpiritApiRequestTransfer $firstSpiritApiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        return $this->getFactory()
            ->createUrlResolverFirstSpiritReader()
            ->getIdentifierByTypeAndId($firstSpiritApiRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $firstSpiritApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getIdentifierByUrl(FirstSpiritApiRequestTransfer $firstSpiritApiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        return $this->getFactory()
            ->createUrlResolverFirstSpiritReader()
            ->getIdentifierByUrl($firstSpiritApiRequestTransfer);
    }
}
