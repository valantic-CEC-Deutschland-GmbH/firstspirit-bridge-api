<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Reader;

use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiPaginationTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Spryker\Client\CmsPageSearch\CmsPageSearchClientInterface;
use Spryker\Client\CmsStorage\CmsStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Symfony\Component\HttpFoundation\Response;
use ValanticSpryker\Shared\FirstSpiritApi\FirstSpiritApiConstants;
use ValanticSpryker\Zed\CmsFirstSpiritApi\CmsFirstSpiritApiConfig;

class CmsFirstSpiritReader implements CmsFirstSpiritReaderInterface
{
    /**
     * @var \Spryker\Client\CmsStorage\CmsStorageClientInterface
     */
    private CmsStorageClientInterface $cmsStorageClient;

    /**
     * @var \Spryker\Client\Store\StoreClientInterface
     */
    private StoreClientInterface $storeClient;

    /**
     * @var \Spryker\Client\CmsPageSearch\CmsPageSearchClientInterface
     */
    private CmsPageSearchClientInterface $cmsPageSearchClient;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    private LocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Client\CmsStorage\CmsStorageClientInterface $cmsStorageClient
     * @param \Spryker\Client\Store\StoreClientInterface $storeClient
     * @param \Spryker\Client\CmsPageSearch\CmsPageSearchClientInterface $cmsPageSearchClient
     * @param \Spryker\Zed\Locale\Business\LocaleFacadeInterface $localeFacade
     */
    public function __construct(
        CmsStorageClientInterface $cmsStorageClient,
        StoreClientInterface $storeClient,
        CmsPageSearchClientInterface $cmsPageSearchClient,
        LocaleFacadeInterface $localeFacade
    ) {
        $this->cmsStorageClient = $cmsStorageClient;
        $this->storeClient = $storeClient;
        $this->cmsPageSearchClient = $cmsPageSearchClient;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getCmsPagesByIds(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        $cmsFirstSpiritCollectionTransfer = new FirstSpiritApiCollectionTransfer();

        $locale = $this->getLocaleFromRequestParameters($apiRequestTransfer->getQueryData());
        $store = $this->storeClient->getCurrentStore()->getName();

        if ($apiRequestTransfer->getQueryType() === FirstSpiritApiConstants::QUERY_TYPE_IDS) {
            $ids = $this->extractIdsFromRequestPath($apiRequestTransfer);
        } else {
            $ids = $this->findCmsPageIdsBySearchQuery($apiRequestTransfer, $cmsFirstSpiritCollectionTransfer);
        }

        return $this->findCmsPagesByProvidedIds($ids, $cmsFirstSpiritCollectionTransfer, $locale, $store);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer $cmsFirstSpiritCollectionTransfer
     *
     * @return array<int>
     */
    private function findCmsPageIdsBySearchQuery(
        FirstSpiritApiRequestTransfer $apiRequestTransfer,
        FirstSpiritApiCollectionTransfer $cmsFirstSpiritCollectionTransfer
    ): array {
        $page = (int)($apiRequestTransfer->getQueryData()[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGINATION_KEY] ?? 1);

        $searchQuery = $apiRequestTransfer->getQueryData()['q'] ?? '';

        $result = $this->cmsPageSearchClient->search($searchQuery, compact('page'));

        $foundCmsPages = $result['cms_pages'] ?? [];

        $foundPageCount = count($foundCmsPages);

        $this->populatePaginationTransferObject($cmsFirstSpiritCollectionTransfer, $page, $foundPageCount);

        return array_column($foundCmsPages, 'id_cms_page');
    }

    /**
     * @param array $cmsPageIds
     * @param \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer $cmsFirstSpiritCollectionTransfer
     * @param string $locale
     * @param string $store
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    private function findCmsPagesByProvidedIds(
        array $cmsPageIds,
        FirstSpiritApiCollectionTransfer $cmsFirstSpiritCollectionTransfer,
        string $locale,
        string $store
    ): FirstSpiritApiCollectionTransfer {
        $cmsPageIds = array_map(static fn ($pageId) => (int)$pageId, $cmsPageIds);

        $cmsPageStorageTransfers = $this->cmsStorageClient->getCmsPageStorageByIds($cmsPageIds, $locale, $store);

        if (!$cmsPageStorageTransfers) {
            $cmsFirstSpiritCollectionTransfer->setStatusCode(Response::HTTP_NOT_FOUND);

            return $cmsFirstSpiritCollectionTransfer;
        }

        return $this->mapCmsStorageTransferToApiCollectionTransfer($cmsFirstSpiritCollectionTransfer, $cmsPageStorageTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return array<string>
     */
    private function extractIdsFromRequestPath(FirstSpiritApiRequestTransfer $apiRequestTransfer): array
    {
        $path = $apiRequestTransfer->getPath();

        return explode(',', $path);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer $cmsFirstSpiritCollectionTransfer
     * @param array $cmsPageStorageTransfers
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    private function mapCmsStorageTransferToApiCollectionTransfer(
        FirstSpiritApiCollectionTransfer $cmsFirstSpiritCollectionTransfer,
        array $cmsPageStorageTransfers
    ): FirstSpiritApiCollectionTransfer {
        foreach ($cmsPageStorageTransfers as $cmsPageStorageTransfer) {
            $cmsPageName = $cmsPageStorageTransfer->getName();
            $cmsPageId = $cmsPageStorageTransfer->getIdCmsPage();
            $cmsPageUrl = $cmsPageStorageTransfer->getUrl();

            $cmsFirstSpiritCollectionTransfer->addData([
                'id' => (string)$cmsPageId,
                'label' => $cmsPageName,
                'extract' => $cmsPageUrl,
            ]);
        }

        return $cmsFirstSpiritCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer $cmsFirstSpiritCollectionTransfer
     * @param int $page
     * @param int $foundPageCount
     *
     * @return void
     */
    private function populatePaginationTransferObject(FirstSpiritApiCollectionTransfer $cmsFirstSpiritCollectionTransfer, int $page, int $foundPageCount): void
    {
        $paginationTransfer = new FirstSpiritApiPaginationTransfer();

        $paginationTransfer->setItemsPerPage(12);
        $paginationTransfer->setTotal($foundPageCount);
        $paginationTransfer->setPage($page);

        $cmsFirstSpiritCollectionTransfer->setPagination($paginationTransfer);
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
