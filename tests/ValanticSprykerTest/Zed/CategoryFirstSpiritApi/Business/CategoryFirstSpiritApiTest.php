<?php

namespace ValanticSprykerTest\Zed\CategoryFirstSpiritApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Spryker\Zed\Locale\Business\LocaleFacade;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Http\HttpConstants;
use ValanticSprykerTest\Zed\CategoryFirstSpiritApi\CategoryFirstSpiritApiBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group ValanticSprykerTest
 * @group Zed
 * @group CategoryFirstSpiritApi
 * @group Business
 * @group CategoryFirstSpiritApiTest
 * Add your own group annotations below this line
 * @group FirstSpiritApi
 */
class CategoryFirstSpiritApiTest extends Unit
{
    protected const BASE_URL = 'http://backend-api.de.spryker.local/first-spirit-api/api/';
    protected const RESOURCE = 'categories';
    protected const USERNAME = 'spryker';
    protected const PASSWORD = 'secret';

    protected Client $client;

    protected CategoryFirstSpiritApiBusinessTester $tester;

    protected function setUp(): void
    {
        $this->client = new Client();
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testCategoryIndexEndpointWorkingFine(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $this->createCategory([
                'name' => 'test category ' . $i,
            ]);
        }

        $this->tester->getLocator()->queue()->facade()->startTask('publish');
        $this->tester->getLocator()->queue()->facade()->startTask('sync.storage.category');

        $response = $this->getResponse();
        $expectedPageSize = 10;
        self::assertEquals($response->getStatusCode(), 200);
        self::assertCount($expectedPageSize, json_decode($response->getBody()->getContents(), true));
        $responseHeaders = $this->getResponseHeaders($response);
        self::assertGreaterThan($expectedPageSize, (int)$responseHeaders[HttpConstants::HEADER_X_TOTAL][0]);
        self::assertSame('1', $responseHeaders[HttpConstants::HEADER_X_HAS_NEXT][0]);
    }

    /**
     * @return void
     */
    public function testCategoryIndexEndpointReturningCorrectNumberOfCategoriesWhenFilteredByParentId(): void
    {
        // Arrange
        $category1 = $this->createCategory([
            'name' => 'test category 1',
        ]);

        $category2 = $this->createCategory([
            'name' => 'test category 2',
            'parentCategoryNode' => $category1->getCategoryNode(),
        ]);

        $category3 = $this->createCategory([
            'name' => 'test category 3',
            'parentCategoryNode' => $category1->getCategoryNode(),
        ]);

        $this->tester->getLocator()->queue()->facade()->startTask('publish');
        $this->tester->getLocator()->queue()->facade()->startTask('sync.storage.category');

        $query = [
            'lang' => $this->getLangParameter(),
            'parentId' => $category1->getCategoryNode()->getIdCategoryNode(),
            'page' => 1,
        ];

        $params = '/';

        // Act
        $response = $this->getResponse($params, query: $query);

        // Assert
        $retrievedData = $this->getResponseData($response);

        $expectedData = [
            [
                'id' => $category2->getCategoryNode()->getIdCategoryNode(),
                'label' => 'test category 2',
            ],
            [
                'id' => $category3->getCategoryNode()->getIdCategoryNode(),
                'label' => 'test category 3',
            ],
        ];

        self::assertEquals($response->getStatusCode(), 200);
        self::assertCount(2, $retrievedData);
        $responseHeaders = $this->getResponseHeaders($response);
        self::assertSame(2, (int)$responseHeaders[HttpConstants::HEADER_X_TOTAL][0]);
        self::assertSame('', $responseHeaders[HttpConstants::HEADER_X_HAS_NEXT][0]);
        $ids = array_column($retrievedData, 'id');
        array_multisort($ids, SORT_ASC, SORT_REGULAR, $retrievedData);
        self::assertEquals($expectedData, $retrievedData);
    }


    /**
     * @return void
     */
    public function testCannotAccessDataWithoutBasicAuth(): void
    {
        $this->expectException(ClientException::class);
        $this->client->request('GET', self::BASE_URL . self::RESOURCE);
    }

    /**
     * @return void
     */
    public function testCategoryDetailEndpointWorkingFine(): void
    {
        $this->tester->getLocator()->queue()->facade()->startTask('publish');
        $this->tester->getLocator()->queue()->facade()->startTask('sync.storage.category');

        // Arrange
        $category1 = $this->createCategory([
            'name' => 'test category 1',
        ]);
        $this->tester->getLocator()->queue()->facade()->startTask('publish');
        $this->tester->getLocator()->queue()->facade()->startTask('sync.storage.category');

        $expectedData = [
            'id' => $category1->getCategoryNode()->getIdCategoryNode(),
            'label' => 'test category 1',
        ];

        $params = '/' . $category1->getCategoryNode()->getIdCategoryNode();

        // ACT
        $response = $this->getResponse($params);
        $retrievedData = $this->getResponseData($response);

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals($expectedData, $retrievedData);
    }

    /**
     * @return void
     */
    public function testCategoryListEndpointReturnsTheCorrectDataWhenRequesting2ExistingNodeIds(): void
    {
        // Arrange
        $category1 = $this->createCategory([
            'name' => 'test category 1',
        ]);

        $category2 = $this->createCategory([
            'name' => 'test category 2',
        ]);

        $this->tester->getLocator()->queue()->facade()->startTask('publish');
        $this->tester->getLocator()->queue()->facade()->startTask('sync.storage.category');

        $expectedData = [
            [
                'id' => $category1->getCategoryNode()->getIdCategoryNode(),
                'label' => 'test category 1',
            ],
            [
                'id' => $category2->getCategoryNode()->getIdCategoryNode(),
                'label' => 'test category 2',
            ],
        ];

        $params = '/ids/' . $category1->getCategoryNode()->getIdCategoryNode() . ',' . $category2->getCategoryNode()->getIdCategoryNode();

        $query = [
            'lang' => $this->getLangParameter(),
        ];
        $response = $this->getResponse($params, query: $query);
        $retrievedData = $this->getResponseData($response);

        self::assertEquals($response->getStatusCode(), 200);
        self::assertEquals($expectedData, $retrievedData);
    }

    /**
     * @return void
     */
    public function testCategoryListEndpointReturnsNullForNonExistingNodeIds(): void
    {
        // Arrange
        $category1 = $this->createCategory([
            'name' => 'test category 1',
        ]);

        $category2 = $this->createCategory([
            'name' => 'test category 2',
        ]);

        $this->tester->getLocator()->queue()->facade()->startTask('publish');
        $this->tester->getLocator()->queue()->facade()->startTask('sync.storage.category');

        $expectedData = [
            [
                'id' => $category1->getCategoryNode()->getIdCategoryNode(),
                'label' => 'test category 1',
            ],
            null,
            [
                'id' => $category2->getCategoryNode()->getIdCategoryNode(),
                'label' => 'test category 2',
            ],
            null,
        ];

        $params = '/ids/' . $category1->getCategoryNode()->getIdCategoryNode() . ',' . -1 . ',' . $category2->getCategoryNode()->getIdCategoryNode() . ',' . -99;

        $query = [
            'lang' => $this->getLangParameter(),
        ];
        $response = $this->getResponse($params, query: $query);
        $retrievedData = $this->getResponseData($response);

        self::assertEquals($response->getStatusCode(), 200);
        self::assertEquals($expectedData, $retrievedData);
    }

    /**
     * @return void
     */
    public function testGetCategoriesWorkingCorrectlyWithTree(): void
    {
        // Arrange
        $category1 = $this->createCategory([
            'name' => 'test category 1',
        ]);

        $category2 = $this->createCategory([
            'name' => 'test category 2',
            'parentCategoryNode' => $category1->getCategoryNode(),
        ]);

        $category3 = $this->createCategory([
            'name' => 'test category 3',
            'parentCategoryNode' => $category1->getCategoryNode(),
        ]);

        codecept_debug(
            'nodes created: ' . $category1->getCategoryNode()->getIdCategoryNode() .
            ', ' . $category2->getCategoryNode()->getIdCategoryNode() .
            ', ' . $category3->getCategoryNode()->getIdCategoryNode(),
        );

        $this->tester->getLocator()->queue()->facade()->startTask('publish');
        $this->tester->getLocator()->queue()->facade()->startTask('sync.storage.category');

        codecept_debug('done with sync');

        $expectedData = [
            [
                'id' => $category2->getCategoryNode()->getIdCategoryNode(),
                'label' => 'test category 2',
            ],
            [
                'id' => $category3->getCategoryNode()->getIdCategoryNode(),
                'label' => 'test category 3',
            ],
        ];

        $params = '/tree';

        $query = [
            'lang' => $this->getLangParameter(),
            'parentId' => $category1->getCategoryNode()->getIdCategoryNode(),
        ];
        // ACT
        $response = $this->getResponse($params, query: $query);

        // Assert
        $retrievedData = $this->getResponseData($response);

        self::assertEquals($response->getStatusCode(), 200);
        $ids = array_column($retrievedData, 'id');
        array_multisort($ids, SORT_ASC, SORT_REGULAR, $retrievedData);
        self::assertSame($expectedData, $retrievedData);
    }

    /**
     * @return void
     */
    public function testHeadWorkingCorrectlyForIds(): void
    {
        $params = '/ids';
        $response = $this->getResponse(method: 'HEAD', params: $params);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testHeadWorkingCorrectlyForTree(): void
    {
        $params = '/tree';
        $response = $this->getResponse(method: 'HEAD', params: $params);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @param string $params
     * @param bool $auth
     * @param array $query
     * @param string $method
     *
     * @return ResponseInterface
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getResponse(string $params = '', bool $auth = true, array $query = [], string $method = 'GET', array $body = []): ResponseInterface
    {
        $credentials = $auth ? [self::USERNAME, self::PASSWORD] : '';

        return $this->client->request($method, self::BASE_URL . self::RESOURCE . $params, [
            'auth' => $credentials,
            'query' => $query,
            'form_params' => $body,
        ]);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    private function getResponseData(ResponseInterface $response)
    {
        return \json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return array
     */
    private function getResponseHeaders(ResponseInterface $response): array
    {
        return $response->getHeaders();
    }

    /**
     * @param array $seedData
     *
     * @return CategoryTransfer
     */
    private function createCategory(array $seedData): CategoryTransfer
    {
        $categoryTransfer = $this->tester->haveLocalizedCategory($seedData);
        $storeTransfer = $this->tester->getLocator()->store()->facade()->getCurrentStore();

        $this->tester->haveCategoryStoreRelation(
            $categoryTransfer->getIdCategory(),
            $storeTransfer->getIdStore(),
        );

        return $categoryTransfer;
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
