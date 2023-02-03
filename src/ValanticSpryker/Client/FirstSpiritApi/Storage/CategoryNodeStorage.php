<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\FirstSpiritApi\Storage;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeStoragePaginationTransfer;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\CategoryStorage\Dependency\Service\CategoryStorageToSynchronizationServiceInterface;
use Spryker\Client\CategoryStorage\Storage\CategoryNodeStorage as SprykerCategoryNodeStorage;
use Spryker\Client\Storage\Redis\Service;
use Spryker\Shared\CategoryStorage\CategoryStorageConstants;
use ValanticSpryker\Client\FirstSpiritApi\Dependency\Client\CategoryStorageToStorageInterface;

class CategoryNodeStorage extends SprykerCategoryNodeStorage implements CategoryNodeStorageInterface
{
    /**
     * @var \ValanticSpryker\Client\FirstSpiritApi\Dependency\Client\CategoryStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @param \ValanticSpryker\Client\FirstSpiritApi\Dependency\Client\CategoryStorageToStorageInterface $storageClient
     * @param \Spryker\Client\CategoryStorage\Dependency\Service\CategoryStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(CategoryStorageToStorageInterface $storageClient, CategoryStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->storageClient = $storageClient;
        parent::__construct($storageClient, $synchronizationService);
    }

    /**
     * @param string $localeName
     * @param string $storeName
     * @param int $page
     * @param int $pageSize
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStoragePaginationTransfer
     */
    public function getAllCategories(string $localeName, string $storeName, int $page, int $pageSize): CategoryNodeStoragePaginationTransfer
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setLocale($localeName)
            ->setStore($storeName);

        $key = $this->synchronizationService
            ->getStorageKeyBuilder(CategoryStorageConstants::CATEGORY_NODE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);

        $keys = $this->storageClient->getAllChildrenKeys($key);
        $keys = $this->stripKVStringFromKeys($keys);
        $pages = array_chunk($keys, $pageSize);
        if ($page <= count($pages)) {
            $categoryNodes = $this->storageClient->getMulti($pages[$page - 1]);
            $nodes = new ArrayObject();
            foreach ($categoryNodes as $categoryNode) {
                $nodes->append(
                    $this->mapCategoryNodeStorageDataToCategoryNodeStorageTransfer(
                        $categoryNode,
                        $localeName,
                        $storeName,
                    ),
                );
            }

            return (new CategoryNodeStoragePaginationTransfer())
                ->setNodes($nodes)
                ->setTotal(count($keys));
        }

        return new CategoryNodeStoragePaginationTransfer();
    }

    /**
     * @param array<int> $categoryNodeIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<string|null>
     */
    protected function getStorageDataByNodeIds(array $categoryNodeIds, string $localeName, string $storeName): array
    {
        $categoryNodeKeys = [];
        foreach ($categoryNodeIds as $categoryNodeId) {
            $categoryNodeKeys[] = $this->generateKey($categoryNodeId, $localeName, $storeName);
        }

        return $this->storageClient->getMulti($categoryNodeKeys);
    }

    /**
     * @param string|null $categoryNodeStorageData
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer|null
     */
    protected function mapCategoryNodeStorageDataToCategoryNodeStorageTransfer(
        ?string $categoryNodeStorageData,
        string $localeName,
        string $storeName
    ): ?CategoryNodeStorageTransfer {
        if ($categoryNodeStorageData === null) {
            return null;
        }

        return parent::mapCategoryNodeStorageDataToCategoryNodeStorageTransfer($categoryNodeStorageData, $localeName, $storeName);
    }

    /**
     * @param array<string> $keys
     *
     * @return array<string>
     */
    private function stripKVStringFromKeys(array $keys): array
    {
        return str_replace(Service::KV_PREFIX, '', $keys);
    }
}
