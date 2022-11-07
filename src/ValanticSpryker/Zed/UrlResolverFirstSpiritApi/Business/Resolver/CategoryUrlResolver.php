<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Resolver;

use Generated\Shared\Transfer\UrlResolverFirstSpiritApiAttributeTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Client\CategoryStorage\CategoryStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\UrlResolverFirstSpiritApiConfig;

class CategoryUrlResolver implements UrlResolverInterface
{
    /**
     * @var \Spryker\Client\CategoryStorage\CategoryStorageClientInterface
     */
    private CategoryStorageClientInterface $categoryStorageClient;

    /**
     * @var \Spryker\Client\Store\StoreClientInterface
     */
    private StoreClientInterface $storeClient;

    /**
     * @param \Spryker\Client\CategoryStorage\CategoryStorageClientInterface $categoryStorageClient
     * @param \Spryker\Client\Store\StoreClientInterface $storeClient
     */
    public function __construct(CategoryStorageClientInterface $categoryStorageClient, StoreClientInterface $storeClient)
    {
        $this->categoryStorageClient = $categoryStorageClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return bool
     */
    public function isApplicable(UrlStorageTransfer $urlStorageTransfer): bool
    {
        return $urlStorageTransfer->getFkResourceCategorynode() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return \Generated\Shared\Transfer\UrlResolverFirstSpiritApiAttributeTransfer
     */
    public function resolveUrlAttributes(UrlStorageTransfer $urlStorageTransfer): UrlResolverFirstSpiritApiAttributeTransfer
    {
        $locale = $urlStorageTransfer->getLocaleName();
        $storeName = $this->getStoreName();

        $categoryNodeStorageTransfer = $this->categoryStorageClient
            ->getCategoryNodeById($urlStorageTransfer->getFkResourceCategorynode(), $locale, $storeName);

        $categoryId = $categoryNodeStorageTransfer
            ->getIdCategory();

        $transfer = new UrlResolverFirstSpiritApiAttributeTransfer();

        $transfer->setId($categoryId);
        $transfer->setlang($locale);
        $transfer->setType(UrlResolverFirstSpiritApiConfig::STORE_CATEGORY_TYPE);

        return $transfer;
    }

    /**
     * @return string
     */
    private function getStoreName(): string
    {
        return $this->storeClient
            ->getCurrentStore()
            ->getName();
    }
}
