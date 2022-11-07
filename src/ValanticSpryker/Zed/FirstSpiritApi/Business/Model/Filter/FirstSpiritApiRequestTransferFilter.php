<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Filter;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;

class FirstSpiritApiRequestTransferFilter implements FirstSpiritApiRequestTransferFilterInterface
{
    /**
     * @var array<\ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiRequestTransferFilterPluginInterface>
     */
    protected $apiRequestTransferFilterPlugins;

    /**
     * @param array<\ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiRequestTransferFilterPluginInterface> $apiRequestTransferFilterPlugins
     */
    public function __construct(array $apiRequestTransferFilterPlugins)
    {
        $this->apiRequestTransferFilterPlugins = $apiRequestTransferFilterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer
     */
    public function filter(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiRequestTransfer
    {
        foreach ($this->apiRequestTransferFilterPlugins as $apiRequestTransferFilterPlugin) {
            $apiRequestTransfer = $apiRequestTransferFilterPlugin->filter($apiRequestTransfer);
        }

        return $apiRequestTransfer;
    }
}
