<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CmsFirstSpiritApi\Business;

use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiDataTransfer;
use Generated\Shared\Transfer\FirstSpiritApiItemTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;

interface CmsFirstSpiritApiFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstSpiritApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function addCmsPage(FirstSpiritApiDataTransfer $apiDataTransfer): FirstSpiritApiItemTransfer;

    /**
     * @api
     *
     * @param int $id
     * @param \Generated\Shared\Transfer\FirstSpiritApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function updateCmsPage(int $id, FirstSpiritApiDataTransfer $apiDataTransfer): FirstSpiritApiItemTransfer;

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function deleteCmsPage(int $id): FirstSpiritApiItemTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $ids
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getCmsPagesByIds(FirstSpiritApiRequestTransfer $ids): FirstSpiritApiCollectionTransfer;
}
