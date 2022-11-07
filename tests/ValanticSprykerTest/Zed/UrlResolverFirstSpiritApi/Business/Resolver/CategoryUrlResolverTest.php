<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Zed\UrlResolverFirstSpiritApi\Business\Resolver;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Mockery;
use Pyz\Client\CategoryStorage\CategoryStorageClient;
use Spryker\Client\Store\StoreClient;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Resolver\CategoryUrlResolver;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\UrlResolverFirstSpiritApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group ValanticSprykerTest
 * @group Zed
 * @group UrlResolverFirstSpiritApi
 * @group Business
 * @group Resolver
 * @group CategoryUrlResolverTest
 * Add your own group annotations below this line
 * @group FirstSpiritApi
 */
class CategoryUrlResolverTest extends Unit
{
    /**
     * @return void
     */
    public function testIsApplicableOnlyForCategory(): void
    {
        $urlStorageTransfer = (new UrlStorageTransfer())->setFkResourceCategorynode(1);

        $resolver = new CategoryUrlResolver(new CategoryStorageClient(), new StoreClient());
        $response = $resolver->isApplicable($urlStorageTransfer);

        $this->assertTrue($response);
    }

    /**
     * @return void
     */
    public function testIsApplicableOnlyForCategoryFailure(): void
    {
        $urlStorageTransfer = new UrlStorageTransfer();

        $resolver = new CategoryUrlResolver(new CategoryStorageClient(), new StoreClient());
        $response = $resolver->isApplicable($urlStorageTransfer);

        $this->assertFalse($response);
    }

    /**
     * @return void
     */
    public function testResolveUrlAttributes(): void
    {
        $urlStorageTransfer = (new UrlStorageTransfer())
            ->setLocaleName('de_DE')
            ->setFkResourceCategorynode(1);

        $resolver = new CategoryUrlResolver($this->getCategoryStorageClientMock(), new StoreClient());
        $response = $resolver->resolveUrlAttributes($urlStorageTransfer);

        $this->assertSame(1, $response->getId());
        $this->assertSame('de_DE', $response->getLang());
        $this->assertSame(UrlResolverFirstSpiritApiConfig::STORE_CATEGORY_TYPE, $response->getType());
    }

    /**
     * @return \Pyz\Client\CategoryStorage\CategoryStorageClient
     */
    private function getCategoryStorageClientMock(): CategoryStorageClient
    {
        $categoryNodeStorageTransfer = (new CategoryNodeStorageTransfer())->setIdCategory(1);

        return Mockery::mock(CategoryStorageClient::class)
            ->shouldReceive('getCategoryNodeById')
            ->once()
            ->andReturn($categoryNodeStorageTransfer)
            ->getMock();
    }
}
