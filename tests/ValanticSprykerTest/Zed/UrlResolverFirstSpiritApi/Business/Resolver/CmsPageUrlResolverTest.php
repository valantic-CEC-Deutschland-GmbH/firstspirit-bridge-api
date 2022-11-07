<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Zed\UrlResolverFirstSpiritApi\Business\Resolver;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UrlStorageTransfer;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Resolver\CmsPageUrlResolver;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\UrlResolverFirstSpiritApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group ValanticSprykerTest
 * @group Zed
 * @group UrlResolverFirstSpiritApi
 * @group Business
 * @group Resolver
 * @group CmsPageUrlResolverTest
 * Add your own group annotations below this line
 * @group FirstSpiritApi
 */
class CmsPageUrlResolverTest extends Unit
{
    /**
     * @return void
     */
    public function testIsApplicableOnlyForCmsPage(): void
    {
        $urlStorageTransfer = (new UrlStorageTransfer())->setFkResourcePage(1);

        $resolver = new CmsPageUrlResolver();
        $response = $resolver->isApplicable($urlStorageTransfer);

        $this->assertTrue($response);
    }

    /**
     * @return void
     */
    public function testIsApplicableOnlyForCmsPageFailure(): void
    {
        $urlStorageTransfer = new UrlStorageTransfer();

        $resolver = new CmsPageUrlResolver();
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
            ->setFkResourcePage(1);

        $resolver = new CmsPageUrlResolver();
        $response = $resolver->resolveUrlAttributes($urlStorageTransfer);

        $this->assertSame(1, $response->getId());
        $this->assertSame('de_DE', $response->getLang());
        $this->assertSame(UrlResolverFirstSpiritApiConfig::STORE_CONTENT_TYPE, $response->getType());
    }
}
