<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\CategoryStorage;

use Generated\Shared\Transfer\CategoryNodeStoragePaginationTransfer;
use Spryker\Client\CategoryStorage\CategoryStorageClient as SprykerCategoryStorageClient;
use ValanticSpryker\Client\CategoryStorage\CategoryStorageClientInterface;

/**
 * @method \ValanticSpryker\Client\CategoryStorage\CategoryStorageFactory getFactory()
 */
class CategoryStorageClient extends SprykerCategoryStorageClient implements CategoryStorageClientInterface
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
    public function getAllCategories(string $localeName, string $storeName, int $page, int $pageSize): CategoryNodeStoragePaginationTransfer
    {
        return $this->getFactory()
            ->createCategoryNodeStorage()
            ->getAllCategories($localeName, $storeName, $page, $pageSize);
    }
}
