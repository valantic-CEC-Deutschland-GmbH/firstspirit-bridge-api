<?php


declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\RestApiResource;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface;

/**
 * @method \ValanticSpryker\Zed\FirstSpiritApi\Communication\FirstSpiritApiCommunicationFactory getFactory()
 * @method \ValanticSpryker\Zed\FirstSpiritApi\Business\FirstSpiritApiFacadeInterface getFacade()
 */
class ResourceParametersPreProcessor implements PreProcessorInterface
{
    /**
     * Maps all remaining path segments as resource params.
     *
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer
     */
    public function process(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiRequestTransfer
    {
        $path = $apiRequestTransfer->getPath() ?? '';

        $elements = [];
        if ($path !== '') {
            $elements[] = $path;
        }

        if (strpos($path, '/') !== false) {
            $elements = explode('/', $path);
        }

        $apiRequestTransfer->setResourceParameters($elements);

        return $apiRequestTransfer;
    }
}
