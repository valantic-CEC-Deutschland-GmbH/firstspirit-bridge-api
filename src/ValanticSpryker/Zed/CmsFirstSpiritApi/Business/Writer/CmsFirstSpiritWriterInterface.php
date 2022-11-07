<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Writer;

use Generated\Shared\Transfer\FirstSpiritApiDataTransfer;
use Generated\Shared\Transfer\FirstSpiritApiItemTransfer;

interface CmsFirstSpiritWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function addCmsPage(FirstSpiritApiDataTransfer $apiDataTransfer): FirstSpiritApiItemTransfer;

    /**
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
}
