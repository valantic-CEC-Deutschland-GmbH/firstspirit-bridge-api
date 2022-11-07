<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Generated\Shared\Transfer\FirstSpiritApiResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \ValanticSpryker\Zed\FirstSpiritApi\Business\FirstSpiritApiBusinessFactory getFactory()
 */
class FirstSpiritApiFacade extends AbstractFacade implements FirstSpiritApiFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer
     */
    public function dispatch(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiResponseTransfer
    {
        return $this->getFactory()
            ->createDispatcher()
            ->dispatch($apiRequestTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer
     */
    public function filterApiRequestTransfer(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiRequestTransfer
    {
        return $this->getFactory()
            ->createRequestTransferFilter()
            ->filter(clone $apiRequestTransfer);
    }
}
