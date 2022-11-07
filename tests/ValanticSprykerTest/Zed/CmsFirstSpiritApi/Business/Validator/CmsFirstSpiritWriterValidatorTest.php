<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Zed\CmsFirstSpiritApi\Business\Validator;

use Codeception\Test\Unit;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Validator\CmsFirstSpiritWriterValidator;
use ValanticSpryker\Zed\CmsFirstSpiritApi\CmsFirstSpiritApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group ValanticSprykerTest
 * @group Zed
 * @group CmsFirstSpiritApi
 * @group Business
 * @group Validator
 * @group CmsFirstSpiritWriterValidatorTest
 * Add your own group annotations below this line
 * @group FirstSpiritApi
 */
class CmsFirstSpiritWriterValidatorTest extends Unit
{
    /**
     * @return void
     */
    public function testValidateTrue(): void
    {
        $data = [
            CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_LABEL => 'test-label',
            CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_TEMPLATE => 'test-template',
            'test' => 'test',
        ];

        $validator = new CmsFirstSpiritWriterValidator();
        $response = $validator->validate($data);

        $this->assertTrue($response);
    }

    /**
     * @return void
     */
    public function testValidateFalse(): void
    {
        $data = [
            CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_TEMPLATE => 'test-template',
            'test' => 'test',
        ];

        $validator = new CmsFirstSpiritWriterValidator();
        $response = $validator->validate($data);

        $this->assertFalse($response);
    }
}
