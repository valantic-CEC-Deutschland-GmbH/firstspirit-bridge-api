<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductFirstSpiritApi\Business\Mapper;

use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;

interface ProductFirstSpiritApiBusinessMapperInterface
{
    /**
     * @param array $searchResult
     * @param \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer $firstSpiritApiCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function mapSearchResultArrayToFirstSpiritApiCollectionTransfer(
        array $searchResult,
        FirstSpiritApiCollectionTransfer $firstSpiritApiCollectionTransfer
    ): FirstSpiritApiCollectionTransfer;

    /**
     * @param array $searchResultProduct
     * @param array $productResponseArray
     * @param string $localeName
     *
     * @return array
     */
    public function mapSearchProductArrayToFirstSpiritApiProductArray(
        array $searchResultProduct,
        array $productResponseArray,
        string $localeName
    ): array;

    /**
     * @param array $productConcreteStorage
     * @param array $productResponseArray
     *
     * @return array
     */
    public function mapProductConcreteStorageArrayToFirstSpiritApiProductArray(
        array $productConcreteStorage,
        array $productResponseArray
    ): array;
}
