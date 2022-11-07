<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Zed\UrlResolverFirstSpiritApi\Business\Resolver;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UrlStorageTransfer;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Resolver\ProductUrlResolver;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\UrlResolverFirstSpiritApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group ValanticSprykerTest
 * @group Zed
 * @group UrlResolverFirstSpiritApi
 * @group Business
 * @group Resolver
 * @group ProductUrlResolverTest
 * Add your own group annotations below this line
 * @group FirstSpiritApi
 */
class ProductUrlResolverTest extends Unit
{
    /**
     * @return void
     */
    public function testIsApplicableOnlyForProduct(): void
    {
        $urlStorageTransfer = (new UrlStorageTransfer())->setFkResourceProductAbstract(1);

        $resolver = new ProductUrlResolver();
        $response = $resolver->isApplicable($urlStorageTransfer);

        $this->assertTrue($response);
    }

    /**
     * @return void
     */
    public function testIsApplicableOnlyForProductFailure(): void
    {
        $urlStorageTransfer = new UrlStorageTransfer();

        $resolver = new ProductUrlResolver();
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
            ->setFkResourceProductAbstract(1);

        $resolver = new ProductUrlResolver();
        $response = $resolver->resolveUrlAttributes($urlStorageTransfer);

        $this->assertSame(1, $response->getId());
        $this->assertSame('de_DE', $response->getLang());
        $this->assertSame(UrlResolverFirstSpiritApiConfig::STORE_PRODUCT_TYPE, $response->getType());
    }
}
