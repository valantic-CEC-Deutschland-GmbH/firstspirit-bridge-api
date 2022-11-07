<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Validator;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;

interface FirstSpiritApiValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\FirstSpiritApiValidationErrorTransfer>
     */
    public function validate(FirstSpiritApiRequestTransfer $apiRequestTransfer): array;
}
