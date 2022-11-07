<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductFirstSpiritApi\Business;

use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \ValanticSpryker\Zed\ProductFirstSpiritApi\Business\ProductFirstSpiritApiBusinessFactory getFactory()
 */
class ProductFirstSpiritApiFacade extends AbstractFacade implements ProductFirstSpiritApiFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getProductsByIds(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        return $this->getFactory()
            ->createProductReader()
            ->getProductsByIds($apiRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getAllProducts(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        return $this->getFactory()
            ->createProductReader()
            ->getAllProducts($apiRequestTransfer);
    }
}
