<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CategoryFirstSpiritApi\Business\Model\Reader;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiItemTransfer;
use Generated\Shared\Transfer\FirstSpiritApiPaginationTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Spryker\Client\CategoryStorage\CategoryStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Symfony\Component\HttpFoundation\Response;
use ValanticSpryker\Client\FirstSpiritApi\FirstSpiritApiClientInterface;
use ValanticSpryker\Zed\CmsFirstSpiritApi\CmsFirstSpiritApiConfig;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;

class CategoryFirstSpiritReader
{
    /**
     * @var \Spryker\Client\CategoryStorage\CategoryStorageClientInterface
     */
    private CategoryStorageClientInterface $storageClient;

    /**
     * @var \ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig
     */
    private FirstSpiritApiConfig $firstSpiritApiConfig;

    /**
     * @var \Spryker\Client\Store\StoreClientInterface
     */
    private StoreClientInterface $storeClient;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    private LocaleFacadeInterface $localeFacade;

    /**
     * @var \ValanticSpryker\Client\FirstSpiritApi\FirstSpiritApiClientInterface
     */
    private FirstSpiritApiClientInterface $firstSpiritApiClient;

    /**
     * @param \Spryker\Client\CategoryStorage\CategoryStorageClientInterface $storageClient
     * @param \Spryker\Zed\Locale\Business\LocaleFacadeInterface $localeFacade
     * @param \ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig $firstSpiritApiConfig
     * @param \Spryker\Client\Store\StoreClientInterface $storeClient
     * @param \ValanticSpryker\Client\FirstSpiritApi\FirstSpiritApiClientInterface $firstSpiritApiClient
     */
    public function __construct(
        CategoryStorageClientInterface $storageClient,
        LocaleFacadeInterface $localeFacade,
        FirstSpiritApiConfig $firstSpiritApiConfig,
        StoreClientInterface $storeClient,
        FirstSpiritApiClientInterface $firstSpiritApiClient
    ) {
        $this->storageClient = $storageClient;
        $this->firstSpiritApiConfig = $firstSpiritApiConfig;
        $this->storeClient = $storeClient;
        $this->localeFacade = $localeFacade;
        $this->firstSpiritApiClient = $firstSpiritApiClient;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getCategoriesByIds(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        $ids = explode(',', $apiRequestTransfer->getPath());
        if (!is_array($ids)) {
            return new FirstSpiritApiCollectionTransfer();
        }
        $ids = array_map(static fn ($id) => (int)$id, $ids);
        $result = new FirstSpiritApiCollectionTransfer();
        $parameters = $apiRequestTransfer->getQueryData();
        $lang = $this->getLocaleFromRequestParameters($parameters);

        $categories = $this->firstSpiritApiClient->getCategoryNodeByIds($ids, $lang, $this->storeClient->getCurrentStore()->getName());

        if (count($categories) === 0) {
            $result->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        foreach ($ids as $id) {
            $categoryNodeData = null;
            $category = $categories[$id] ?? null;

            if ($category) {
                $categoryNodeData = $this->mapCategoryNodeData($category);
            }

            $result->addData($categoryNodeData);
        }

        return $result;
    }

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function getCategoryById(int $id): FirstSpiritApiItemTransfer
    {
        $lang = $this->getDefaultLocale();
        $category = $this->firstSpiritApiClient->getCategoryNodeById($id, $lang, $this->storeClient->getCurrentStore()->getName());

        if ($category->getIdCategory() === null) {
            return (new FirstSpiritApiItemTransfer())->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return (new FirstSpiritApiItemTransfer())->setData($this->mapCategoryNodeData($category));
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getCategoryTree(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        $parameters = $apiRequestTransfer->getQueryData();
        $parentId = isset($parameters['parentId']) ? (int)$parameters['parentId'] : -1;
        $lang = $this->getLocaleFromRequestParameters($parameters);

        $categoryTree = $this->storageClient->getCategories($lang, $this->storeClient->getCurrentStore()->getName());

        if ($parentId > -1) {
            $categoryTree = $this->findSubTreeByParentId($categoryTree, $parentId);
        }

        $result = new FirstSpiritApiCollectionTransfer();

        if ($categoryTree->count() === 0) {
            $result->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        foreach ($categoryTree as $category) {
            if ($category === null) {
                continue;
            }
            $result->addData($this->mapCategoryTreeData($category));
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getAllCategories(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        $parameters = $apiRequestTransfer->getQueryData();
        $lang = $this->getLocaleFromRequestParameters($parameters);
        $page = isset($parameters['page']) ? (int)$parameters['page'] : 1;
        $parentId = isset($parameters['parentId']) ? (int)$parameters['parentId'] : -1;
        $pageSize = $this->firstSpiritApiConfig->getPagingSize();

        if ($parentId > -1) {
            return $this->getAllCategoriesFilteredByParentId($lang, $page, $pageSize, $parentId);
        }

        $categoryNodeStorageTransfer = $this->firstSpiritApiClient->getAllCategories($lang, $this->storeClient->getCurrentStore()->getName(), $page, $pageSize);

        $result = new FirstSpiritApiCollectionTransfer();

        foreach ($categoryNodeStorageTransfer->getNodes() as $category) {
            $result->addData($this->mapCategoryNodeData($category));
        }
        $result->setPagination(
            (new FirstSpiritApiPaginationTransfer())
                ->setTotal($categoryNodeStorageTransfer->getTotal())
                ->setPage($page)
                ->setItemsPerPage($pageSize),
        );

        return $result;
    }

    /**
     * @param string $localeName
     * @param int $page
     * @param int $pageSize
     * @param int $parentId
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function getAllCategoriesFilteredByParentId(string $localeName, int $page, int $pageSize, int $parentId): FirstSpiritApiCollectionTransfer
    {
        $categoryTree = $this->storageClient->getCategories($localeName, $this->storeClient->getCurrentStore()->getName());

        $categoryTree = $this->findSubTreeByParentId($categoryTree, $parentId);
        $nodes = [];
        $this->getFlatCategoryListFromTree($categoryTree, $nodes);

        $result = new FirstSpiritApiCollectionTransfer();
        $pages = array_chunk($nodes, $pageSize);
        if ($page <= count($pages)) {
            /** @var \Generated\Shared\Transfer\CategoryNodeStorageTransfer|null $category */
            foreach ($pages[$page - 1] as $category) {
                $result->addData($this->mapCategoryNodeData($category));
            }
            $result->setPagination(
                (new FirstSpiritApiPaginationTransfer())
                    ->setTotal(count($nodes))
                    ->setPage($page)
                    ->setItemsPerPage($pageSize),
            );
        }

        return $result;
    }

    /**
     * @param array $parameters
     *
     * @return string
     */
    private function getLocaleFromRequestParameters(array $parameters): string
    {
        if (!isset($parameters[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_LANGUAGE])) {
            return $this->getDefaultLocale();
        }

        $lang = $parameters[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_LANGUAGE];
        $locales = $this->localeFacade->getAvailableLocales();

        return $locales[$lang] ?? $this->getDefaultLocale();
    }

    /**
     * @return string
     */
    private function getDefaultLocale(): string
    {
        return $this->localeFacade->getCurrentLocaleName();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer|null $categoryNodeData
     *
     * @return array|null
     */
    private function mapCategoryNodeData(?CategoryNodeStorageTransfer $categoryNodeData): ?array
    {
        if ($categoryNodeData === null) {
            return null;
        }
        $result = [];

        $result['id'] = $categoryNodeData->getNodeId();
        $result['label'] = $categoryNodeData->getName();

        return $result;
    }

    /**
     * @param \ArrayObject $categoryTree
     * @param int $parentId
     *
     * @return \ArrayObject
     */
    private function findSubTreeByParentId(ArrayObject $categoryTree, int $parentId): ArrayObject
    {
        foreach ($categoryTree as $category) {
            if ($category->getNodeId() === $parentId) {
                return $category->getChildren();
            }
            $subTreeResult = $this->findSubTreeByParentId($category->getChildren(), $parentId);
            if ($subTreeResult->count() > 0) {
                return $subTreeResult;
            }
        }

        return new ArrayObject();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $category
     *
     * @return array
     */
    private function mapCategoryTreeData(CategoryNodeStorageTransfer $category): array
    {
        $result = [];
        $result['id'] = $category->getNodeId();
        $result['label'] = $category->getName();
        $children = $category->getChildren();
        if (count($children) > 0) {
            $result['children'] = [];
        }
        foreach ($children as $child) {
            $result['children'][] = $this->mapCategoryTreeData($child);
        }

        return $result;
    }

    /**
     * @param \ArrayObject $categoryTreeNodeList
     * @param array $result
     *
     * @return array
     */
    private function getFlatCategoryListFromTree(ArrayObject $categoryTreeNodeList, array &$result): array
    {
        foreach ($categoryTreeNodeList as $categoryTreeNode) {
            $result[] = $categoryTreeNode;
            $this->getFlatCategoryListFromTree($categoryTreeNode->getChildren(), $result);
        }

        return $result;
    }
}
