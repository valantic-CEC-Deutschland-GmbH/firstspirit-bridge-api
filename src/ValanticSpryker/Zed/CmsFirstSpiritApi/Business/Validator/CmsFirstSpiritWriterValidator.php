<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Validator;

use ValanticSpryker\Zed\CmsFirstSpiritApi\CmsFirstSpiritApiConfig;

class CmsFirstSpiritWriterValidator implements CmsFirstSpiritWriterValidatorInterface
{
    /**
     * @var array<string>
     */
    protected array $required = [
        CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_LABEL,
        CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_TEMPLATE,
    ];

    /**
     * @param array $data
     *
     * @return bool
     */
    public function validate(array $data): bool
    {
        foreach ($this->required as $requiredVal) {
            if (!array_key_exists($requiredVal, $data)) {
                return false;
            }
        }

        return true;
    }
}
