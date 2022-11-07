<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Validator;

interface CmsFirstSpiritWriterValidatorInterface
{
    /**
     * @param array $data
     *
     * @return bool
     */
    public function validate(array $data): bool;
}
