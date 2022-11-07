<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CategoryFirstSpiritApi\Business;

use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiItemTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;

interface CategoryFirstSpiritApiFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getCategoriesByIds(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer;

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function getCategoryById(int $id): FirstSpiritApiItemTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getAllCategories(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getCategoryTree(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer;
}
