<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Validator;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;

class FirstSpiritApiValidator implements FirstSpiritApiValidatorInterface
{
    /**
     * @var array<\ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiValidatorPluginInterface>
     */
    protected $apiValidatorPlugins;

    /**
     * @param array<\ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiValidatorPluginInterface> $apiValidatorPlugins
     */
    public function __construct(array $apiValidatorPlugins)
    {
        $this->apiValidatorPlugins = $apiValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\FirstSpiritApiValidationErrorTransfer>
     */
    public function validate(FirstSpiritApiRequestTransfer $apiRequestTransfer): array
    {
        $resourceName = $apiRequestTransfer->getResourceOrFail();
        foreach ($this->apiValidatorPlugins as $apiValidatorPlugin) {
            if (mb_strtolower($apiValidatorPlugin->getResourceName()) === mb_strtolower($resourceName)) {
                return $apiValidatorPlugin->validate($apiRequestTransfer);
            }
        }

        return [];
    }
}
