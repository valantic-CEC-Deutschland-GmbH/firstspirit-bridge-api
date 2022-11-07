<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductFirstSpiritApi\Business\Mapper;

use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiPaginationTransfer;
use Generated\Shared\Transfer\PaginationSearchResultTransfer;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use ValanticSpryker\Shared\ProductFirstSpiritApi\ProductFirstSpiritApiConstants;

class ProductFirstSpiritApiBusinessMapper implements ProductFirstSpiritApiBusinessMapperInterface
{
    private const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    private const KEY_ID_PRODUCT_CONCRETE = 'id_product_concrete';
    private const KEY_ID = 'id';
    private const KEY_LABEL = 'label';
    private const KEY_URL = 'url';
    private const KEY_EXTRACT = 'extract';
    private const KEY_IMAGE = 'image';
    private const KEY_THUMBNAIL = 'thumbnail';
    private const KEY_NAME = 'name';

    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    private ProductStorageClientInterface $productStorageClient;

    /**
     * @param \Spryker\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
     */
    public function __construct(ProductStorageClientInterface $productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param array $searchResult
     * @param \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer $firstSpiritApiCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function mapSearchResultArrayToFirstSpiritApiCollectionTransfer(
        array $searchResult,
        FirstSpiritApiCollectionTransfer $firstSpiritApiCollectionTransfer
    ): FirstSpiritApiCollectionTransfer {
        if (array_key_exists(ProductFirstSpiritApiConstants::PAGINATION, $searchResult)) {
            $firstSpiritApiCollectionTransfer->setPagination(
                $this->mapPaginationSearchResultTransferToFirstSpiritApiPagination(
                    $searchResult[ProductFirstSpiritApiConstants::PAGINATION],
                    new FirstSpiritApiPaginationTransfer(),
                ),
            );
        }
        if (array_key_exists(ProductFirstSpiritApiConstants::RESOURCE_PRODUCTS, $searchResult)) {
            foreach ($searchResult[ProductFirstSpiritApiConstants::RESOURCE_PRODUCTS] as $searchResultProduct) {
                $productResponseArray = $this->mapSearchProductArrayToFirstSpiritApiProductArray($searchResultProduct, [], $searchResult[ProductFirstSpiritApiConstants::LOCALE_NAME]);
                if (array_key_exists(self::KEY_ID, $productResponseArray)) {
                    $firstSpiritApiCollectionTransfer->addData($productResponseArray);
                }
            }
        }

        return $firstSpiritApiCollectionTransfer;
    }

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
    ): array {
        $productAbstractStorage = $this->productStorageClient
            ->findProductAbstractViewTransfer(
                $searchResultProduct[self::KEY_ID_PRODUCT_ABSTRACT],
                $localeName,
            );

        if ($productAbstractStorage !== null) {
            if ($productAbstractStorage->getIdProductConcrete() !== null) {
                $productConcreteStorage = $this->productStorageClient
                    ->findProductConcreteViewTransfer(
                        $productAbstractStorage->getIdProductConcrete(),
                        $localeName,
                    );
                if ($productConcreteStorage !== null) {
                    $productResponseArray[self::KEY_ID] = $productConcreteStorage->getSku();
                    $productResponseArray[self::KEY_LABEL] = $productConcreteStorage->getName();
                    $productResponseArray[self::KEY_EXTRACT] = basename($productConcreteStorage->getUrl());
                }
            }
            $images = $productAbstractStorage->getImages()->getArrayCopy()[0];
            if ($images !== null) {
                $productResponseArray[self::KEY_IMAGE] = $images->getExternalUrlLarge();
                $productResponseArray[self::KEY_THUMBNAIL] = $images->getExternalUrlSmall();
            }
        }

        return $productResponseArray;
    }

    /**
     * @param array $productConcreteStorage
     * @param array $productResponseArray
     *
     * @return array
     */
    public function mapProductConcreteStorageArrayToFirstSpiritApiProductArray(
        array $productConcreteStorage,
        array $productResponseArray
    ): array {
        $productResponseArray[self::KEY_ID] = $productConcreteStorage[ProductFirstSpiritApiConstants::KEY_SKU];
        $productResponseArray[self::KEY_LABEL] = $productConcreteStorage[self::KEY_NAME];
        $productResponseArray[self::KEY_EXTRACT] = basename($productConcreteStorage[self::KEY_URL]);

        $productConcreteViewTransfer = $this->productStorageClient
            ->findProductConcreteViewTransfer(
                $productConcreteStorage[self::KEY_ID_PRODUCT_CONCRETE],
                $productConcreteStorage[ProductFirstSpiritApiConstants::LOCALE_NAME],
            );

        if ($productConcreteViewTransfer !== null) {
            $images = $productConcreteViewTransfer->getImages()->getArrayCopy();
            if (count($images) > 0) {
                $firstKey = array_key_first($images);
                $productResponseArray[self::KEY_IMAGE] = $images[$firstKey]->getExternalUrlLarge();
                $productResponseArray[self::KEY_THUMBNAIL] = $images[$firstKey]->getExternalUrlSmall();
            }
        }

        return $productResponseArray;
    }

    /**
     * @param \Generated\Shared\Transfer\PaginationSearchResultTransfer $paginationSearchResultTransfer
     * @param \Generated\Shared\Transfer\FirstSpiritApiPaginationTransfer $firstSpiritApiPaginationTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiPaginationTransfer
     */
    private function mapPaginationSearchResultTransferToFirstSpiritApiPagination(
        PaginationSearchResultTransfer $paginationSearchResultTransfer,
        FirstSpiritApiPaginationTransfer $firstSpiritApiPaginationTransfer
    ): FirstSpiritApiPaginationTransfer {
        $firstSpiritApiPaginationTransfer->setPage($paginationSearchResultTransfer->getCurrentPage());
        $firstSpiritApiPaginationTransfer->setPageTotal($paginationSearchResultTransfer->getMaxPage());
        $firstSpiritApiPaginationTransfer->setItemsPerPage($paginationSearchResultTransfer->getCurrentItemsPerPage());
        $firstSpiritApiPaginationTransfer->setTotal($paginationSearchResultTransfer->getNumFound());
        if ($paginationSearchResultTransfer->getMaxPage() > 1) {
            if ($paginationSearchResultTransfer->getCurrentPage() > 1) {
                $prevPage = $paginationSearchResultTransfer->getCurrentPage() - 1;
                $firstSpiritApiPaginationTransfer->setPrev((string)$prevPage);
            }
            $nextPage = $paginationSearchResultTransfer->getCurrentPage() + 1;
            if ($nextPage <= $paginationSearchResultTransfer->getMaxPage()) {
                $firstSpiritApiPaginationTransfer->setNext((string)$nextPage);
            }
        }
        $firstSpiritApiPaginationTransfer->setFirst('1');
        $firstSpiritApiPaginationTransfer->setLast((string)$paginationSearchResultTransfer->getMaxPage());

        return $firstSpiritApiPaginationTransfer;
    }
}
