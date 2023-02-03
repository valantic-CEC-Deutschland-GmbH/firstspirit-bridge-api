<?php


declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\RestApiResource;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface;

/**
 * @method \ValanticSpryker\Zed\FirstSpiritApi\Communication\FirstSpiritApiCommunicationFactory getFactory()
 * @method \ValanticSpryker\Zed\FirstSpiritApi\Business\FirstSpiritApiFacadeInterface getFacade()
 */
class ResourcePreProcessor implements PreProcessorInterface
{
    /**
     * Resolves the first part of the URL path as resource.
     *
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer
     */
    public function process(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiRequestTransfer
    {
        // GET orders/1
        $path = $apiRequestTransfer->getPath() ?? '';

        $resource = $path;

        $position = strpos($path, '/');
        if ($position !== false) {
            $resource = substr($path, 0, $position);
            $path = substr($path, strlen($resource) + 1);
        } else {
            $path = '';
        }

        $apiRequestTransfer->setResource($resource);

        $apiRequestTransfer->setPath($path);

        return $apiRequestTransfer;
    }
}
