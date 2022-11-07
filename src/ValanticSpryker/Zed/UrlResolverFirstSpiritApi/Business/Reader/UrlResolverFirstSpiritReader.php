<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Reader;

use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Client\CategoryStorage\CategoryStorageClientInterface;
use Spryker\Client\CmsStorage\CmsStorageClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Client\UrlStorage\UrlStorageClientInterface;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Symfony\Component\HttpFoundation\Response;
use ValanticSpryker\Zed\CmsFirstSpiritApi\CmsFirstSpiritApiConfig;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\UrlResolverFirstSpiritApiConfig;

class UrlResolverFirstSpiritReader implements UrlResolverFirstSpiritReaderInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    private ProductStorageClientInterface $productStorageClient;

    /**
     * @var \Spryker\Client\CategoryStorage\CategoryStorageClientInterface
     */
    private CategoryStorageClientInterface $categoryStorageClient;

    /**
     * @var \Spryker\Client\Store\StoreClientInterface
     */
    private StoreClientInterface $storeClient;

    /**
     * @var \Spryker\Client\CmsStorage\CmsStorageClientInterface
     */
    private CmsStorageClientInterface $cmsStorageClient;

    /**
     * @var \Spryker\Client\UrlStorage\UrlStorageClientInterface
     */
    private UrlStorageClientInterface $urlStorageClient;

    /**
     * @var array<\ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Resolver\UrlResolverInterface>
     */
    private array $urlAttributeResolverArray;

    /**
     * @var \ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig
     */
    private FirstSpiritApiConfig $firstSpiritApiConfig;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    private LocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
     * @param \Spryker\Client\CategoryStorage\CategoryStorageClientInterface $categoryStorageClient
     * @param \Spryker\Client\Store\StoreClientInterface $storeClient
     * @param \Spryker\Client\CmsStorage\CmsStorageClientInterface $cmsStorageClient
     * @param \Spryker\Client\UrlStorage\UrlStorageClientInterface $urlClient
     * @param \Spryker\Zed\Locale\Business\LocaleFacadeInterface $localeFacade
     * @param array<\ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Resolver\UrlResolverInterface> $urlAttributeResolverPlugins
     * @param \ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig $firstSpiritApiConfig
     */
    public function __construct(
        ProductStorageClientInterface $productStorageClient,
        CategoryStorageClientInterface $categoryStorageClient,
        StoreClientInterface $storeClient,
        CmsStorageClientInterface $cmsStorageClient,
        UrlStorageClientInterface $urlClient,
        LocaleFacadeInterface $localeFacade,
        array $urlAttributeResolverPlugins,
        FirstSpiritApiConfig $firstSpiritApiConfig
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->categoryStorageClient = $categoryStorageClient;
        $this->storeClient = $storeClient;
        $this->cmsStorageClient = $cmsStorageClient;
        $this->urlStorageClient = $urlClient;
        $this->localeFacade = $localeFacade;
        $this->urlAttributeResolverArray = $urlAttributeResolverPlugins;
        $this->firstSpiritApiConfig = $firstSpiritApiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $firstSpiritApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getIdentifierByTypeAndId(FirstSpiritApiRequestTransfer $firstSpiritApiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        $collectionTransfer = new FirstSpiritApiCollectionTransfer();
        $transferData = $firstSpiritApiRequestTransfer->getQueryData();

        $type = (string)($transferData['type'] ?? null);
        $id = (int)($transferData['id'] ?? null);
        $locale = $this->getLocaleFromRequestParameters($transferData);

        if ($this->typeOrIdInvalid($type, $id)) {
            $collectionTransfer->setStatusCode(Response::HTTP_UNAUTHORIZED);

            return $collectionTransfer;
        }

        if ($type === UrlResolverFirstSpiritApiConfig::STORE_PRODUCT_TYPE) {
            return $this->retrieveProductType($id, $collectionTransfer, $locale);
        }

        if ($type === UrlResolverFirstSpiritApiConfig::STORE_CATEGORY_TYPE) {
            return $this->retrieveCategoryType($id, $collectionTransfer, $locale);
        }

        if ($type === UrlResolverFirstSpiritApiConfig::STORE_CONTENT_TYPE) {
            return $this->retrieveCmsContent($id, $collectionTransfer, $locale);
        }

        return $collectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $firstSpiritApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getIdentifierByUrl(FirstSpiritApiRequestTransfer $firstSpiritApiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        $collectionTransfer = new FirstSpiritApiCollectionTransfer();

        $transferData = $firstSpiritApiRequestTransfer->getQueryData();

        $url = $transferData['url'] ?? null;

        if (!$url) {
            $collectionTransfer->setStatusCode(Response::HTTP_UNAUTHORIZED);

            return $collectionTransfer;
        }

        return $this->retrieveDataByUrl($url, $collectionTransfer);
    }

    /**
     * @param string|null $type
     * @param int|null $id
     *
     * @return bool
     */
    private function typeOrIdInvalid(?string $type, ?int $id): bool
    {
        return !in_array($type, UrlResolverFirstSpiritApiConfig::VALID_STOREFRONT_TYPES, true) || (int)$id <= 0;
    }

    /**
     * @param int $id
     * @param \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer $collectionTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    private function retrieveCategoryType(int $id, FirstSpiritApiCollectionTransfer $collectionTransfer, string $locale): FirstSpiritApiCollectionTransfer
    {
        $storeName = $this->getStoreName();

        $categoryData = $this->categoryStorageClient->getCategoryNodeById((int)$id, $locale, $storeName);
        $categoryUrl = $categoryData->getUrl();

        if ($categoryUrl === null) {
            $collectionTransfer->addData(null);

            return $collectionTransfer;
        }

        $categoryUrl = $this->concatenateWithBaseUrl($categoryUrl);

        $collectionTransfer->setData(['url' => $categoryUrl]);

        return $collectionTransfer;
    }

    /**
     * @param int $id
     * @param \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer $collectionTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    private function retrieveProductType(int $id, FirstSpiritApiCollectionTransfer $collectionTransfer, string $locale): FirstSpiritApiCollectionTransfer
    {
        $productDataArray = $this->productStorageClient->findProductAbstractStorageData((int)$id, $locale);

        $url = $productDataArray['url'] ?? null;

        if ($url === null) {
            $collectionTransfer->addData(null);

            return $collectionTransfer;
        }

        $url = $this->concatenateWithBaseUrl($url);

        $collectionTransfer
            ->setData(["url" => $url]);

        return $collectionTransfer;
    }

    /**
     * @param int $id
     * @param \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer $collectionTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    private function retrieveCmsContent(int $id, FirstSpiritApiCollectionTransfer $collectionTransfer, string $locale): FirstSpiritApiCollectionTransfer
    {
        $ids = [$id];
        $storeName = $this->getStoreName();
        $contentDataArray = $this->cmsStorageClient
            ->getCmsPageStorageByIds($ids, $locale, $storeName);

        $contentUrl = $contentDataArray[0]['url'] ?? null;

        if ($contentUrl === null) {
            $collectionTransfer->addData(null);

            return $collectionTransfer;
        }

        $contentUrl = $this->concatenateWithBaseUrl($contentUrl);

        $collectionTransfer->setData([
            'url' => $contentUrl,
        ]);

        return $collectionTransfer;
    }

    /**
     * @return string|null
     */
    private function getStoreName(): ?string
    {
        return $this->storeClient
            ->getCurrentStore()
            ->getName();
    }

    /**
     * @param string $url
     * @param \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer $collectionTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    private function retrieveDataByUrl(string $url, FirstSpiritApiCollectionTransfer $collectionTransfer): FirstSpiritApiCollectionTransfer
    {
        $urlStorageTransfer = $this->getUrlStorageTransfer($url);

        if ($urlStorageTransfer === null) {
            $collectionTransfer->addData(null);

            return $collectionTransfer;
        }

        $attributeTransfer = null;

        foreach ($this->urlAttributeResolverArray as $attributeResolver) {
            if (!$attributeResolver->isApplicable($urlStorageTransfer)) {
                continue;
            }

            $attributeTransfer = $attributeResolver->resolveUrlAttributes($urlStorageTransfer);
        }

        if (!$attributeTransfer) {
            $collectionTransfer->addData(null);

            return $collectionTransfer;
        }

        $collectionTransfer->setData([
            'lang' => $attributeTransfer->getLang(),
            'type' => $attributeTransfer->getType(),
            'id' => (string)$attributeTransfer->getId(),
        ]);

        return $collectionTransfer;
    }

    /**
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlStorageTransfer|null
     */
    private function getUrlStorageTransfer(string $url): ?UrlStorageTransfer
    {
        $urlStorageTransfer = $this->urlStorageClient->findUrlStorageTransferByUrl($url);

        if (!$urlStorageTransfer) {
            return null;
        }

        if (!$urlStorageTransfer->getFkResourceRedirect()) {
            return $urlStorageTransfer;
        }

        $urlRedirectStorageTransfer = $this->urlStorageClient->findUrlRedirectStorageById(
            $urlStorageTransfer->getFkResourceRedirect(),
        );

        if (!$urlRedirectStorageTransfer) {
            return null;
        }

        return $this->getUrlStorageTransfer($urlRedirectStorageTransfer->getToUrl());
    }

    /**
     * @param string $url
     *
     * @return string
     */
    private function concatenateWithBaseUrl(string $url): string
    {
        return $this->firstSpiritApiConfig->getSpaUrl() . $url;
    }

    /**
     * @param array $parameters
     *
     * @return string
     */
    private function getLocaleFromRequestParameters(array $parameters): string
    {
        $currentLocale = $this->localeFacade->getCurrentLocaleName();
        if (!isset($parameters[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_LANGUAGE])) {
            return $currentLocale;
        }

        $lang = $parameters[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_LANGUAGE];
        $locales = $this->localeFacade->getAvailableLocales();

        return $locales[$lang] ?? $currentLocale;
    }
}
