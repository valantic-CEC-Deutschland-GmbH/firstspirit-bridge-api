<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Communication\Controller;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Generated\Shared\Transfer\FirstSpiritApiResponseTransfer;
use Spryker\Zed\Api\Communication\Controller\AbstractApiController;

/**
 * @method \ValanticSpryker\Zed\FirstSpiritApi\Business\FirstSpiritApiFacadeInterface getFacade()
 * @method \ValanticSpryker\Zed\FirstSpiritApi\Communication\FirstSpiritApiCommunicationFactory getFactory()
 */
class RestController extends AbstractApiController
{
 /**
  * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
  *
  * @return \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer
  */
    public function indexAction(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiResponseTransfer
    {
        return $this->getFacade()->dispatch($apiRequestTransfer);
    }

    /**
     * @return void
     */
    public function deniedAction(): void
    {
    }
}
