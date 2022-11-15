<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\CategoryStorage;

use Generated\Shared\Transfer\CategoryNodeStoragePaginationTransfer;
use Spryker\Client\CategoryStorage\CategoryStorageClientInterface as SprykerCategoryStorageClientInterface;

interface CategoryStorageClientInterface extends SprykerCategoryStorageClientInterface
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
}
