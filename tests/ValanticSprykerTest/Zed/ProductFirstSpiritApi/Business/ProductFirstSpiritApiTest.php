<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Zed\ProductFirstSpiritApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Locale\Business\LocaleFacade;
use ValanticSprykerTest\Zed\ProductFirstSpiritApi\ProductFirstSpiritApiBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group ValanticSprykerTest
 * @group Zed
 * @group ProductFirstSpiritApi
 * @group Business
 * @group ProductFirstSpiritApiTest
 * Add your own group annotations below this line
 * @group FirstSpiritApi
 */
class ProductFirstSpiritApiTest extends Unit
{
    protected const BASE_URL = 'http://backend-api.de.spryker.local/first-spirit-api/api/';
    protected const USERNAME = 'spryker';
    protected const PASSWORD = 'secret';
    protected const RESOURCE = 'products';

    /**
     * @var \ValanticSprykerTest\Zed\ProductFirstSpiritApi\ProductFirstSpiritApiBusinessTester
     */
    protected ProductFirstSpiritApiBusinessTester $tester;

    /**
     *
     * @return void
     */
    public function testProductIndexEndpointWorkingFine(): void
    {
        // Arrange
        $storeTransfer = $this->tester->getLocator()->store()->facade()->getCurrentStore();
        $locale = $this->tester->getLocator()->locale()->facade()->getCurrentLocale();

        $product = $this->mockProduct();
        $productAbstractId = $product->getFkProductAbstract();
        $productConcreteId = $product->getIdProductConcrete();

        $this->tester->haveProductImageSet(
            [
                ProductImageSetTransfer::ID_PRODUCT => $productConcreteId,
                ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractId,
            ],
        );

        $categoryTransfer = $this->tester->haveLocalizedCategory(['locale' => $locale]);
        $this->tester->getLocator()->productCategory()->facade()->createProductCategoryMappings(
            $categoryTransfer->getIdCategory(),
            [$productAbstractId]
        );

        $productPrice = $this->tester->createPriceProduct($product);
        $storeRelationTransfer = (new StoreRelationTransfer())
            ->setIdStores([$storeTransfer->getIdStore()])
            ->addStores($storeTransfer);

        $productAbstractTransfer = $this->tester->getProductFacade()->findProductAbstractById($productAbstractId);
        $productAbstractTransfer->setStoreRelation($storeRelationTransfer);
        $productAbstractTransfer->addPrice($productPrice);
        $this->tester->getProductFacade()->saveProductAbstract($productAbstractTransfer);

        $this->tester->getLocator()->productStorage()->facade()->publishAbstractProducts([$productAbstractId]);
        $this->tester->getLocator()->productStorage()->facade()->publishConcreteProducts([$productConcreteId]);
        $this->tester->getLocator()->priceProductStorage()->facade()->publishPriceProductAbstract([$productPrice->getIdProductAbstract()]);
        $this->tester->getLocator()->priceProductStorage()->facade()->publishPriceProductConcrete([$productPrice->getIdProduct()]);
        $this->tester->getLocator()->productImageStorage()->facade()->publishProductAbstractImages([$productAbstractId]);

        $this->tester->getLocator()->productPageSearch()->facade()->publish([$productAbstractId]);
        $this->tester->getLocator()->productPageSearch()->facade()->publishProductConcretes([$productConcreteId]);

        $this->tester->getLocator()->queue()->facade()->startTask('publish');
        $this->tester->getLocator()->queue()->facade()->startTask('sync.storage.product');
        $this->tester->getLocator()->queue()->facade()->startTask('sync.storage.price');
        $this->tester->getLocator()->queue()->facade()->startTask('sync.search.product');

        $query = [
            'q' => $product->getSku(),
            'lang' => $this->getLangParameter(),
        ];

        // Act
        $response = $this->getResponse(self::RESOURCE, true, $query);
        $retrievedData = $this->getResponseData($response);

        // Assert
        self::assertTrue(count($retrievedData) > 0);
        foreach ($retrievedData as $productResponse) {
            self::assertArrayHasKey('id', $productResponse);
            self::assertArrayHasKey('label', $productResponse);
            self::assertArrayHasKey('extract', $productResponse);
        }
        $this->tester->seeResponseCodeIsSuccessful();
    }

    /**
     * @return void
     */
    public function testCannotAccessDataWithoutBasicAuth(): void
    {
        $this->getResponse(self::RESOURCE, false);
        $this->tester->seeResponseCodeIs(401);
    }

    /**
     * @return void
     */
    public function testProductIdsWithSkuEndpointWorkingFine(): void
    {
        // Arrange
        $product = $this->mockProduct();
        $productPrice = $this->tester->createPriceProduct($product);
        $product->addPrice($productPrice);
        $productAbstractId = $product->getFkProductAbstract();
        $productConcreteId = $product->getIdProductConcrete();

        $this->tester->haveProductImageSet(
            [
                ProductImageSetTransfer::ID_PRODUCT => $productConcreteId,
                ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractId,
            ],
        );

        $this->tester->getLocator()->productStorage()->facade()->publishAbstractProducts([$productAbstractId]);
        $this->tester->getLocator()->productStorage()->facade()->publishConcreteProducts([$productConcreteId]);
        $this->tester->getLocator()->priceProductStorage()->facade()->publishPriceProductAbstract([$productPrice->getIdProductAbstract()]);
        $this->tester->getLocator()->priceProductStorage()->facade()->publishPriceProductConcrete([$productPrice->getIdProduct()]);
        $this->tester->getLocator()->productImageStorage()->facade()->publishProductAbstractImages([$productAbstractId]);

        $this->tester->getLocator()->queue()->facade()->startTask('sync.storage.product');
        $this->tester->getLocator()->queue()->facade()->startTask('sync.storage.price');

        $params = '/ids/' . $product->getSku();
        $query = [
            'lang' => $this->getLangParameter(),
        ];

        // Act
        $response = $this->getResponse(self::RESOURCE . $params, true, $query);
        $retrievedData = $this->getResponseData($response);
        $productResponse = $retrievedData[0];

        // Assert
        self::assertArrayHasKey('id', $productResponse);
        self::assertArrayHasKey('label', $productResponse);
        self::assertArrayHasKey('extract', $productResponse);
        self::assertArrayHasKey('image', $productResponse);
        self::assertArrayHasKey('thumbnail', $productResponse);
        self::assertSame($product->getSku(), $productResponse['id']);

        $this->tester->seeResponseCodeIsSuccessful();
    }

    /**
     * @return void
     */
    public function testProductIdsWithNonExistingSkusReturningNullForNonExistingSkus(): void
    {

        // Arrange
        $product = $this->mockProduct();
        $productPrice = $this->tester->createPriceProduct($product);
        $product->addPrice($productPrice);
        $productAbstractId = $product->getFkProductAbstract();
        $productConcreteId = $product->getIdProductConcrete();

        $this->tester->haveProductImageSet(
            [
                ProductImageSetTransfer::ID_PRODUCT => $productConcreteId,
                ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractId,
            ],
        );

        $this->tester->getLocator()->productStorage()->facade()->publishAbstractProducts([$productAbstractId]);
        $this->tester->getLocator()->productStorage()->facade()->publishConcreteProducts([$productConcreteId]);
        $this->tester->getLocator()->priceProductStorage()->facade()->publishPriceProductAbstract([$productPrice->getIdProductAbstract()]);
        $this->tester->getLocator()->priceProductStorage()->facade()->publishPriceProductConcrete([$productPrice->getIdProduct()]);
        $this->tester->getLocator()->productImageStorage()->facade()->publishProductAbstractImages([$productAbstractId]);

        $this->tester->getLocator()->queue()->facade()->startTask('sync.storage.product');
        $this->tester->getLocator()->queue()->facade()->startTask('sync.storage.price');

        $params = '/ids/' . $product->getSku() . ',' . -99;
        $query = [
            'lang' => $this->getLangParameter(),
        ];

        // Act
        $response = $this->getResponse(self::RESOURCE . $params, true, $query);
        $retrievedData = $this->getResponseData($response);
        $productResponse = $retrievedData[0];

        // Assert
        self::assertArrayHasKey('id', $productResponse);
        self::assertArrayHasKey('label', $productResponse);
        self::assertArrayHasKey('extract', $productResponse);
        self::assertArrayHasKey('image', $productResponse);
        self::assertArrayHasKey('thumbnail', $productResponse);
        self::assertSame($product->getSku(), $productResponse['id']);

        self::assertCount(2, $retrievedData);
        self::assertNull($retrievedData[1]);

        $this->tester->seeResponseCodeIsSuccessful();
    }

    /**
     * @return void
     */
    public function testHeadWorkingCorrectly(): void
    {
        $this->getHeadResponse(self::RESOURCE . '/ids');
        $this->tester->seeResponseCodeIsSuccessful();
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
     * @param string $resource
     * @param bool $auth
     * @param array $query
     *
     * @return string
     */
    private function getHeadResponse(string $resource = '', bool $auth = true, array $query = []): string
    {
        if ($auth) {
            $this->tester->amHttpAuthenticated(self::USERNAME, self::PASSWORD);
        }

        return $this->tester->sendHead(self::BASE_URL . $resource, $query);
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
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    private function mockProduct(): ProductConcreteTransfer
    {
        $localeTransfer = (new LocaleFacade)->getCurrentLocale();

        return $this->tester->haveFullProduct([
            'isActive' => true,
            'localizedAttributes' => [
                [
                    'locale' => $localeTransfer,
                    'name' => 'simply name product concrete',
                    'description' => 'some description',
                    'application' => 'super application',
                    'ingredients' => 'little ingredients',
                    'isSearchable' => true,
                ],
            ],
        ],
            [
                'isActive' => true,
                'localizedAttributes' => [
                    [
                        'locale' => $localeTransfer,
                        'name' => 'simply name product abstract',
                        'description' => 'some description',
                        'application' => 'super application',
                        'ingredients' => 'little ingredients',
                        'isSearchable' => true,
                    ],
                ],
            ]);
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
