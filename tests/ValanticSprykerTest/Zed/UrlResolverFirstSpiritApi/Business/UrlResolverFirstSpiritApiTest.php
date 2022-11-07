<?php

namespace ValanticSprykerTest\Zed\UrlResolverFirstSpiritApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery;
use Orm\Zed\Url\Persistence\SpyUrl;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Pyz\Zed\Queue\Business\QueueFacade;
use Spryker\Zed\Cms\Business\CmsFacade;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\UrlStorage\Business\UrlStorageFacade;
use ValanticSprykerTest\Zed\UrlResolverFirstSpiritApi\UrlResolverFirstSpiritApiBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group UrlResolverFirstSpiritApi
 * @group Business
 * @group UrlResolverFirstSpiritApiTest
 * Add your own group annotations below this line
 * @group FirstSpiritApi
 */
class UrlResolverFirstSpiritApiTest extends Unit
{
    /**
     * @var \ValanticSprykerTest\Zed\UrlResolverFirstSpiritApi\UrlResolverFirstSpiritApiBusinessTester
     */
    protected UrlResolverFirstSpiritApiBusinessTester $tester;

    /**
     * @var string
     */
    protected const BASE_URL = 'http://backend-api.de.spryker.local/first-spirit-api/api/';

    /**
     * @var string
     */
    protected const RESOURCE_STOREFRONT = 'storefront-url';

    /**
     * @var string
     */
    protected const RESOURCE_LOOKUP = 'lookup-url';

    /**
     * @var string
     */
    protected const USERNAME = 'spryker';

    /**
     * @var string
     */
    protected const PASSWORD = 'secret';

    /**
     * @return void
     */
    public function testStorefrontIndexWithoutParametersNotWorking(): void
    {
        $this->getResponse(resource: self::RESOURCE_STOREFRONT);
        $this->tester->seeResponseCodeIs(401);
    }

    /**
     * @return void
     */
    public function testCannotAccessDataWithoutBasicAuth(): void
    {
        $this->getResponse(resource: self::RESOURCE_STOREFRONT, auth: false);
        $this->tester->seeResponseCodeIs(401);
    }

    /**
     * @return void
     *
     * 1. Query the data from DB and compare the data from API
     * 2. Create my own data and persist and sync to Redis and then use API
     */
    public function testStorefrontProductEndpointWorkingFine(): void
    {
        $product = $this->tester->haveFullProduct();
        $productId = $product->getFkProductAbstract();

        $this->tester->getLocator()
            ->productStorage()
            ->facade()
            ->publishAbstractProducts([$productId]);

        $query = [
            'type' => 'product',
            'id' => $productId,
            'lang' => $this->getLangParameter(),
        ];

        $this->tester->getLocator()
            ->queue()
            ->facade()
            ->startTask('sync.storage.product');

        $response = $this->getResponse(resource: self::RESOURCE_STOREFRONT, query: $query);
        $this->getResponseData($response);

        $this->tester->seeResponseCodeIsSuccessful();
    }

    /**
     * @return void
     */
    public function testStoreFrontCategoryEndpointWorkingFine(): void
    {
        $categoryTransfer = $this->tester->haveLocalizedCategory();
        $categoryNodeId = $categoryTransfer->getCategoryNode()->getIdCategoryNode();
        $urlModel = $this->tester->haveUrl(['fk_resource_categorynode' => $categoryNodeId, 'url' => '/test-url' . '-' . random_int(1, 1000)]);

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->tester->haveCategoryStoreRelation($categoryTransfer->getIdCategory(), $storeTransfer->getIdStore());

        /** Publish data */
        $this->tester->getLocator()
            ->categoryStorage()
            ->facade()
            ->publish([$categoryNodeId]);

        $this->tester->getLocator()
            ->urlStorage()
            ->facade()
            ->publishUrl([$urlModel->getIdUrl()]);

        /** Sync Data */
        $this->tester->getLocator()
            ->queue()
            ->facade()
            ->startTask('sync.storage.category');

        $this->tester->getLocator()
            ->queue()
            ->facade()
            ->startTask('sync.storage.url');

        $query = [
            'type' => 'category',
            'id' => $categoryNodeId,
            'lang' => $this->getLangParameter(),
        ];

        $response = $this->getResponse(resource: self::RESOURCE_STOREFRONT, query: $query);
        $retrievedData = $this->getResponseData($response);

        $this->assertEquals($urlModel->getUrl(), $this->getUrlPath($retrievedData['url'] ?? null));

        $this->tester->seeResponseCodeIsSuccessful();
    }

    /**
     * @return void
     */
    public function testStoreFrontCmsPageEndpointWorkingFine(): void
    {
        $model = SpyCmsTemplateQuery::create()
            ->filterByTemplateName('Placeholders Title & Content')
            ->filterByTemplatePath('@Cms/templates/placeholders-title-content/placeholders-title-content.twig')
            ->findOneOrCreate();

        $model->save();
        $templateId = $model->getIdCmsTemplate();

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $cmsPageModel = $this->tester->haveCmsPage([
            CmsPageTransfer::FK_TEMPLATE => $templateId,
            CmsPageTransfer::IS_ACTIVE => true,
            CmsPageAttributesTransfer::LOCALE_NAME => (new LocaleFacade())->getCurrentLocale()->getLocaleName(),
            CmsPageAttributesTransfer::FK_LOCALE => (new LocaleFacade())->getCurrentLocale()->getIdLocale(),
            CmsPageTransfer::STORE_RELATION => [
                StoreRelationTransfer::ID_ENTITY => $storeTransfer->getIdStore(),
                StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()],
                StoreRelationTransfer::STORES => [$storeTransfer],
            ],
        ]);

        $cmsPageId = $cmsPageModel->getFkPage();

        $urlModel = SpyURlQuery::create()
            ->findByFkResourcePage($cmsPageId)
            ->getFirst();

        $query = [
            'type' => 'content',
            'id' => $cmsPageId,
            'lang' => $this->getLangParameter(),
        ];

        (new CmsFacade())->publishWithVersion($cmsPageId, '1');

        $this->tester->getLocator()
            ->cmsStorage()
            ->facade()
            ->publish([$cmsPageId]);

        $this->tester->getLocator()
            ->queue()
            ->facade()
            ->startTask('sync.storage.cms');

        $this->tester->getLocator()
            ->queue()
            ->facade()
            ->startTask('sync.storage.url');

        $response = $this->getResponse(resource: self::RESOURCE_STOREFRONT, query: $query);
        $retrievedData = $this->getResponseData($response);

        $expectedUrl = $urlModel->getUrl();
        $this->assertEquals($expectedUrl, $this->getUrlPath($retrievedData['url'] ?? null));

        $this->tester->seeResponseCodeIsSuccessful();
    }

    /**
     * @return void
     */
    public function testLookupIndexWithoutParametersNotWorking(): void
    {
        $this->getResponse(resource: self::RESOURCE_LOOKUP);
        $this->tester->seeResponseCodeIs(401);
    }

    /**
     * @return void
     */
    public function testCannotAccessLookupDataWithoutBasicAuth(): void
    {
        $this->getResponse(resource: self::RESOURCE_LOOKUP, auth: false);
        $this->tester->seeResponseCodeIs(401);
    }

    /**
     * @return void
     */
    public function testLookupEndpointForContentTypeWorkingFine(): void
    {
        $model = SpyCmsTemplateQuery::create()
            ->filterByTemplateName('Placeholders Title & Content')
            ->filterByTemplatePath('@Cms/templates/placeholders-title-content/placeholders-title-content.twig')
            ->findOneOrCreate();

        $model->save();
        $templateId = $model->getIdCmsTemplate();

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $cmsPageModel = $this->tester->haveCmsPage([
            CmsPageTransfer::FK_TEMPLATE => $templateId,
            CmsPageTransfer::IS_ACTIVE => true,
            CmsPageAttributesTransfer::LOCALE_NAME => (new LocaleFacade())->getCurrentLocale()->getLocaleName(),
            CmsPageAttributesTransfer::FK_LOCALE => (new LocaleFacade())->getCurrentLocale()->getIdLocale(),
            CmsPageTransfer::STORE_RELATION => [
                StoreRelationTransfer::ID_ENTITY => $storeTransfer->getIdStore(),
                StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()],
                StoreRelationTransfer::STORES => [$storeTransfer],
            ],
        ]);

        $cmsPageId = $cmsPageModel->getFkPage();

        (new CmsFacade())->publishWithVersion($cmsPageId, '1');

        /** @var SpyUrl $urlModel */
        $urlModel = SpyUrlQuery::create()
            ->findByFkResourcePage($cmsPageId)
            ->getFirst();

        $url = $urlModel->getUrl();

        (new UrlStorageFacade())->publishUrl([$urlModel->getIdUrl()]);

        $this->tester->getLocator()
            ->cmsStorage()
            ->facade()
            ->publish([$cmsPageId]);

        $this->tester->getLocator()
            ->queue()
            ->facade()
            ->startTask('sync.storage.cms');

        $this->tester->getLocator()
            ->queue()
            ->facade()
            ->startTask('sync.storage.url');

        $query = [
            'url' => $url,
        ];

        $expectedData = [
            'type' => 'content',
            'id' => $cmsPageId,
        ];

        $response = $this->getResponse(resource: self::RESOURCE_LOOKUP, query: $query);
        $retrievedData = $this->getResponseData($response);

        $this->tester->seeResponseCodeIsSuccessful();

        self::assertEquals($expectedData['type'], $retrievedData['type'] ?? null);
        self::assertEquals($expectedData['id'], $retrievedData['id'] ?? null);
    }

    public function testLookupEndpointForCategoryTypeWorkingFine(): void
    {
        $categoryModel = $this->tester->haveLocalizedCategory();
        $categoryNodeId = $categoryModel->getCategoryNode()->getIdCategoryNode();
        $urlModel = $this->tester->haveUrl(['fk_resource_categorynode' => $categoryNodeId, 'url' => '/test-url' . '-' . random_int(1, 1000)]);

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->tester->haveCategoryStoreRelation($categoryModel->getIdCategory(), $storeTransfer->getIdStore());

        /** Publish data */
        $this->tester->getLocator()
            ->categoryStorage()
            ->facade()
            ->publish([$categoryNodeId]);

        $this->tester->getLocator()
            ->urlStorage()
            ->facade()
            ->publishUrl([$urlModel->getIdUrl()]);

        /** Sync Data */
        $this->tester->getLocator()
            ->queue()
            ->facade()
            ->startTask('sync.storage.category');

        $this->tester->getLocator()
            ->queue()
            ->facade()
            ->startTask('sync.storage.url');

        $expectedData = [
            'type' => 'category',
            'id' => $categoryModel->getIdCategory(),
        ];

        $query = [
            'url' => $urlModel->getUrl(),
        ];

        $response = $this->getResponse(resource: self::RESOURCE_LOOKUP, query: $query);
        $retrievedData = $this->getResponseData($response);

        $this->tester->seeResponseCodeIsSuccessful();

        self::assertEquals($expectedData['type'], $retrievedData['type'] ?? null);
        self::assertEquals($expectedData['id'], $retrievedData['id'] ?? null);
    }

    public function testLookupEndpointForProductTypeWorkingFine(): void
    {
        $product = $this->tester->haveFullProduct();
        $productId = $product->getFkProductAbstract();

        $this->tester->getLocator()
            ->productStorage()
            ->facade()
            ->publishAbstractProducts([$productId]);

        /** @var SpyUrl $urlModel */
        $urlModel = SpyUrlQuery::create()
            ->findByFkResourceProductAbstract($productId)
            ->getFirst();

        $url = $urlModel->getUrl();

        $query = [
            'url' => $url,
        ];

        $expectedData = [
            'type' => 'product',
            'id' => $productId,
        ];

        (new UrlStorageFacade())->publishUrl([$urlModel->getIdUrl()]);
        (new QueueFacade())->startTask('sync.storage.product');
        (new QueueFacade())->startTask('sync.storage.url');

        $response = $this->getResponse(resource: self::RESOURCE_LOOKUP, query: $query);
        $retrievedData = $this->getResponseData($response);

        $this->tester->seeResponseCodeIsSuccessful();

        self::assertEquals($expectedData['type'], $retrievedData['type'] ?? null);
        self::assertEquals($expectedData['id'], $retrievedData['id'] ?? null);
    }

    /**
     * @return void
     */
    public function testLookuptReturningSuccessHttpAndNull()
    {
        $query = [
            'url' => '/de/asndjakdnadkja-132131'
        ];

        $response = $this->getResponse(self::RESOURCE_LOOKUP, query: $query);
        $retrievedData = $this->getResponseData($response);
        $this->tester->seeResponseCodeIsSuccessful();
        $this->assertEquals([null], $retrievedData);
    }

    /**
     * @return void
     */
    public function testStorefronCategoryReturningSuccessHttpAndNull()
    {
        $query = [
            'type' => 'content',
            'id' => 9999999999,
            'lang' => $this->getLangParameter(),
        ];

        $response = $this->getResponse(self::RESOURCE_STOREFRONT, query: $query);
        $retrievedData = $this->getResponseData($response);
        $this->tester->seeResponseCodeIsSuccessful();
        $this->assertEquals([null], $retrievedData);

    }

    /**
     * @return void
     */
    public function testStorefronProductReturningSuccessHttpAndNull()
    {
        $query = [
            'type' => 'product',
            'id' => 999999,
            'lang' => $this->getLangParameter(),

        ];

        $response = $this->getResponse(self::RESOURCE_STOREFRONT, query: $query);
        $retrievedData = $this->getResponseData($response);
        $this->tester->seeResponseCodeIsSuccessful();
        $this->assertEquals([null], $retrievedData);

    }

    /**
     * @return void
     */
    public function testStorefronContentReturningSuccessHttpAndNull()
    {
        $query = [
            'type' => 'content',
            'id' => 999999,
            'lang' => $this->getLangParameter(),
        ];

        $response = $this->getResponse(self::RESOURCE_STOREFRONT, query: $query);
        $retrievedData = $this->getResponseData($response);
        $this->tester->seeResponseCodeIsSuccessful();
        $this->assertEquals([null], $retrievedData);
    }

    /**
     * @param string $resource
     * @param bool $auth
     * @param array $query
     *
     * @return string
     */
    private function getResponse(string $resource = '', bool $auth = true, array $query = []): string
    {
        if ($auth) {
            $this->tester->amHttpAuthenticated(self::USERNAME, self::PASSWORD);
        }

        return $this->tester->sendGet(self::BASE_URL . $resource, $query);
    }

    /**
     * @param string $response
     *
     * @return mixed
     */
    private function getResponseData(string $response)
    {
        return json_decode($response, true);
    }

    /**
     * @param string $url
     *
     * @return string
     */
    private function getUrlPath(string $url): string
    {
        return parse_url($url, PHP_URL_PATH);
    }

    /**
     * @return string
     */
    private function getLangParameter(): string
    {
        $localeName = (new LocaleFacade())->getCurrentLocaleName();

        return explode('_', $localeName)[0];
    }
}
