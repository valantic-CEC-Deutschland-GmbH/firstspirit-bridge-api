<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;

/**
 * Implement this plugin to add a validation to your first spirit api endpoint.
 */
interface FirstSpiritApiValidatorPluginInterface
{
    /**
     * Specification:
     * - Returns the resource name.
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string;

    /**
     * Specification:
     * - Validates request data.
     * - Returns an array of validation errors in case errors occur.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\FirstSpiritApiValidationErrorTransfer>
     */
    public function validate(FirstSpiritApiRequestTransfer $apiRequestTransfer): array;
}
