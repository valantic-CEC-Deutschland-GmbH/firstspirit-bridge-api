<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\FirstSpiritApi;

use Generated\Shared\Transfer\CategoryNodeStoragePaginationTransfer;
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
}
