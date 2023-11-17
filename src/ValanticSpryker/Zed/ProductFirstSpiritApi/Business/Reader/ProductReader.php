<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductFirstSpiritApi\Business\Reader;

use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Spryker\Client\Catalog\CatalogClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Symfony\Component\HttpFoundation\Response;
use ValanticSpryker\Shared\ProductFirstSpiritApi\ProductFirstSpiritApiConstants;
use ValanticSpryker\Zed\CmsFirstSpiritApi\CmsFirstSpiritApiConfig;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;
use ValanticSpryker\Zed\ProductFirstSpiritApi\Business\Mapper\ProductFirstSpiritApiBusinessMapperInterface;
use ValanticSpryker\Zed\ProductFirstSpiritApi\ProductFirstSpiritApiConfig;

class ProductReader
{
    /**
     * @var string
     */
    private const CATEGORY_ID = 'categoryId';

    /**
     * @var string
     */
    private const CATEGORY = 'category';

    /**
     * @var string
     */
    private const KEY_PRODUCTS_PER_PAGE = 'ipp';

    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    private ProductStorageClientInterface $productStorageClient;

    /**
     * @var \Spryker\Client\Catalog\CatalogClientInterface
     */
    private CatalogClientInterface $catalogClient;

    /**
     * @var \ValanticSpryker\Zed\ProductFirstSpiritApi\Business\Mapper\ProductFirstSpiritApiBusinessMapperInterface
     */
    private ProductFirstSpiritApiBusinessMapperInterface $businessMapper;

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
     * @param \Spryker\Client\Catalog\CatalogClientInterface $catalogClient
     * @param \Spryker\Zed\Locale\Business\LocaleFacadeInterface $localeFacade
     * @param \ValanticSpryker\Zed\ProductFirstSpiritApi\Business\Mapper\ProductFirstSpiritApiBusinessMapperInterface $businessMapper
     * @param \ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig $firstSpiritApiConfig
     * @param array<\ValanticSpryker\Zed\ProductFirstSpiritApi\Communication\Dependency\Plugin\QueryExpanderPluginInterface> $queryDataExpanderPlugins
     */
    public function __construct(
        ProductStorageClientInterface $productStorageClient,
        CatalogClientInterface $catalogClient,
        LocaleFacadeInterface $localeFacade,
        ProductFirstSpiritApiBusinessMapperInterface $businessMapper,
        FirstSpiritApiConfig $firstSpiritApiConfig,
        private array $queryDataExpanderPlugins = []
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->catalogClient = $catalogClient;
        $this->localeFacade = $localeFacade;
        $this->businessMapper = $businessMapper;
        $this->firstSpiritApiConfig = $firstSpiritApiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getProductsByIds(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        $collectionTransfer = new FirstSpiritApiCollectionTransfer();
        $localeName = $this->getLocaleFromRequestParameters($apiRequestTransfer->getQueryData());

        $skus = explode(',', $apiRequestTransfer->getPath());
        if (is_array($skus)) {
            foreach ($skus as $sku) {
                $productConcrete = $this->productStorageClient->findProductConcreteStorageDataByMapping(
                    ProductFirstSpiritApiConstants::KEY_SKU,
                    $sku,
                    $localeName,
                );
                if ($productConcrete === null) {
                    $collectionTransfer->addData(null);

                    continue;
                }
                $productConcrete[ProductFirstSpiritApiConstants::LOCALE_NAME] = $localeName;
                $productResponseArray = $this->businessMapper->mapProductConcreteStorageArrayToFirstSpiritApiProductArray($productConcrete, []);
                $collectionTransfer->addData($productResponseArray);
            }
        }

        return $collectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getAllProducts(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        $collectionTransfer = new FirstSpiritApiCollectionTransfer();

        $queryData = $apiRequestTransfer->getQueryData();
        $queryData = $this->adjustQueryData($queryData);
        $searchString = $this->getRequestParameter($queryData, ProductFirstSpiritApiConfig::QUERY_STRING_PARAMETER);
        $searchResult = $this->catalogClient->catalogSearch($searchString, $queryData);

        if (count($searchResult[ProductFirstSpiritApiConstants::RESOURCE_PRODUCTS]) === 0) {
            $collectionTransfer->setStatusCode(Response::HTTP_NOT_FOUND);

            return $collectionTransfer;
        }
        $searchResult[ProductFirstSpiritApiConstants::LOCALE_NAME] = $this->getLocaleFromRequestParameters($apiRequestTransfer->getQueryData());

        return $this->businessMapper->mapSearchResultArrayToFirstSpiritApiCollectionTransfer($searchResult, $collectionTransfer);
    }

    /**
     * @param array $queryData
     * @param string $parameterName
     *
     * @return string
     */
    private function getRequestParameter(array $queryData, string $parameterName): string
    {
        if (array_key_exists($parameterName, $queryData)) {
            return $queryData[$parameterName];
        }

        return '';
    }

    /**
     * @param array $queryData
     * @param string $oldKey
     * @param string $newKey
     *
     * @return array
     */
    private function replaceOldArrayKeyWithNewArrayKey(array $queryData, string $oldKey, string $newKey): array
    {
        if (array_key_exists($oldKey, $queryData)) {
            $keys = array_keys($queryData);
            $keys[array_search($oldKey, $keys, true)] = $newKey;

            return array_combine($keys, $queryData);
        }

        return $queryData;
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

    /**
     * @param $queryData
     *
     * @return array
     */
    protected function adjustQueryData($queryData): array
    {
        $queryData[self::KEY_PRODUCTS_PER_PAGE] = $this->firstSpiritApiConfig->getPagingSize();
        $queryData = $this->replaceOldArrayKeyWithNewArrayKey($queryData, self::CATEGORY_ID, self::CATEGORY);

        $this->extendQueryData($queryData);

        return $queryData;
    }

    /**
     * @param array $queryData
     *
     * @return array
     */
    private function extendQueryData(array $queryData): array
    {
        foreach ($this->queryDataExpanderPlugins as $queryExpanderPlugin) {
            $queryData = $queryExpanderPlugin->expandQueryData($queryData);
        }

        return $queryData;
    }
}
