<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CategoryFirstSpiritApi\Business;

use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiItemTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \ValanticSpryker\Zed\CategoryFirstSpiritApi\Business\CategoryFirstSpiritApiBusinessFactory getFactory()
 */
class CategoryFirstSpiritApiFacade extends AbstractFacade implements CategoryFirstSpiritApiFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getCategoriesByIds(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        return $this->getFactory()
            ->createCategoryReader()
            ->getCategoriesByIds($apiRequestTransfer);
    }

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function getCategoryById(int $id): FirstSpiritApiItemTransfer
    {
        return $this->getFactory()
            ->createCategoryReader()
            ->getCategoryById($id);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getAllCategories(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        return $this->getFactory()
            ->createCategoryReader()
            ->getAllCategories($apiRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getCategoryTree(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        return $this->getFactory()
            ->createCategoryReader()
            ->getCategoryTree($apiRequestTransfer);
    }
}
