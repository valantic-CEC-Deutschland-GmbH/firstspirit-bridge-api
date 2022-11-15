<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\CategoryStorage\Storage;

use Generated\Shared\Transfer\CategoryNodeStoragePaginationTransfer;
use Spryker\Client\CategoryStorage\Storage\CategoryNodeStorageInterface as SprykerCategoryNodeStorageInterface;

interface CategoryNodeStorageInterface extends SprykerCategoryNodeStorageInterface
{
    /**
     * @param string $localeName
     * @param string $storeName
     * @param int $page
     * @param int $pageSize
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStoragePaginationTransfer
     */
    public function getAllCategories(string $localeName, string $storeName, int $page, int $pageSize): CategoryNodeStoragePaginationTransfer;
}
