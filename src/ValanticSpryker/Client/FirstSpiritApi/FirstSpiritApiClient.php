<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\FirstSpiritApi;

use Generated\Shared\Transfer\CategoryNodeStoragePaginationTransfer;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;
use ValanticSpryker\Client\FirstSpiritApi\FirstSpiritApiClientInterface;

/**
 * @method \ValanticSpryker\Client\FirstSpiritApi\FirstSpiritApiFactory getFactory()
 */
class FirstSpiritApiClient extends AbstractClient implements FirstSpiritApiClientInterface
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

    /**
     * @api
     *
     * @param int $idCategoryNode
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById(int $idCategoryNode, string $localeName, string $storeName): CategoryNodeStorageTransfer
    {
        return $this->getFactory()
            ->createCategoryNodeStorage()
            ->getCategoryNodeById($idCategoryNode, $localeName, $storeName);
    }

    /**
     * @api
     *
     * @param array<int> $categoryNodeIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\CategoryNodeStorageTransfer>
     */
    public function getCategoryNodeByIds(array $categoryNodeIds, string $localeName, string $storeName): array
    {
        return $this->getFactory()
            ->createCategoryNodeStorage()
            ->getCategoryNodeByIds($categoryNodeIds, $localeName, $storeName);
    }
}
