<?php

namespace ValanticSprykerTest\Zed\CmsFirstSpiritApi\Business\Generator;

use Codeception\Test\Unit;
use Spryker\Service\UtilText\UtilTextService;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Generator\UrlGenerator;

/**
 * Auto-generated group annotations
 *
 * @group ValanticSprykerTest
 * @group Zed
 * @group CmsFirstSpiritApi
 * @group Business
 * @group UrlGeneratorTest
 * Add your own group annotations below this line
 * @group FirstSpiritApi
 */
class UrlGeneratorTest extends Unit
{
    /**
     * @return void
     */
    public function testUrlGeneratorReturningUrl(): void
    {
        $utilTextService = new UtilTextService();
        $urlGenerator = new UrlGenerator($utilTextService);
        $label = 'default-url';

        $generatedUrl = $urlGenerator->getGeneratedUrl($label);
        $this->assertEquals($label, $generatedUrl);
    }
}
