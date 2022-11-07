<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CmsFirstSpiritApi\Business;

use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiDataTransfer;
use Generated\Shared\Transfer\FirstSpiritApiItemTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \ValanticSpryker\Zed\CmsFirstSpiritApi\Business\CmsFirstSpiritApiBusinessFactory getFactory()
 * @method \ValanticSpryker\Zed\CmsFirstSpiritApi\Persistence\CmsFirstSpiritApiRepositoryInterface getRepository()
 */
class CmsFirstSpiritApiFacade extends AbstractFacade implements CmsFirstSpiritApiFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function addCmsPage(FirstSpiritApiDataTransfer $apiDataTransfer): FirstSpiritApiItemTransfer
    {
        return $this->getFactory()
            ->createCmsFirstSpiritWriter()
            ->addCmsPage($apiDataTransfer);
    }

    /**
     * @param int $id
     * @param \Generated\Shared\Transfer\FirstSpiritApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function updateCmsPage(int $id, FirstSpiritApiDataTransfer $apiDataTransfer): FirstSpiritApiItemTransfer
    {
        return $this->getFactory()
            ->createCmsFirstSpiritWriter()
            ->updateCmsPage($id, $apiDataTransfer);
    }

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function deleteCmsPage(int $id): FirstSpiritApiItemTransfer
    {
        return $this->getFactory()
            ->createCmsFirstSpiritWriter()
            ->deleteCmsPage($id);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $firstSpiritApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getCmsPagesByIds(FirstSpiritApiRequestTransfer $firstSpiritApiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        return $this->getFactory()
            ->createCmsFirstSpiritReader()
            ->getCmsPagesByIds($firstSpiritApiRequestTransfer);
    }
}
