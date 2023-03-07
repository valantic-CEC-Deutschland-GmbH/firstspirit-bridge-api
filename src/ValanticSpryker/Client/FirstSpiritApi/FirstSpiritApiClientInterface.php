<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\FirstSpiritApi;

use Generated\Shared\Transfer\CategoryNodeStoragePaginationTransfer;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;

interface FirstSpiritApiClientInterface
{
    /**
     * Specification:
     *  - Return all category node storage data by locale name and store name.
     *
     * @api
     *
     * @param string $localeName
     * @param string $storeName
     * @param int $page
     * @param int $pageSize
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStoragePaginationTransfer
     */
    public function getAllCategories(string $localeName, string $storeName, int $page, int $pageSize): CategoryNodeStoragePaginationTransfer;

    /**
     * Get a category node by id
     *
     * @api
     *
     * @param int $idCategoryNode
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById(int $idCategoryNode, string $localeName, string $storeName): CategoryNodeStorageTransfer;

    /**
     * get a category node by a set of ids
     *
     * @api
     *
     * @param array<int> $categoryNodeIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\CategoryNodeStorageTransfer>
     */
    public function getCategoryNodeByIds(array $categoryNodeIds, string $localeName, string $storeName): array;
}
