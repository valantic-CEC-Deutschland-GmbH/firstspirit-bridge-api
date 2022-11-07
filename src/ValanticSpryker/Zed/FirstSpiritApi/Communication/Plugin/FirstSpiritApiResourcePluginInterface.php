<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin;

use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiDataTransfer;
use Generated\Shared\Transfer\FirstSpiritApiItemTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Generated\Shared\Transfer\FirstSpiritApiResponseTransfer;

interface FirstSpiritApiResourcePluginInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getResourceName(): string;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstSpiritApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function add(FirstSpiritApiDataTransfer $apiDataTransfer): FirstSpiritApiItemTransfer;

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function get(int $id): FirstSpiritApiItemTransfer;

    /**
     * @api
     *
     * @param int $id
     * @param \Generated\Shared\Transfer\FirstSpiritApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function update(int $id, FirstSpiritApiDataTransfer $apiDataTransfer): FirstSpiritApiItemTransfer;

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function remove(int $id): FirstSpiritApiItemTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function find(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer
     */
    public function head(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiResponseTransfer;
}
