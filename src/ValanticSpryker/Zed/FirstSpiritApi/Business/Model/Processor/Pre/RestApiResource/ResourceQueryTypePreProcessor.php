<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\RestApiResource;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use ValanticSpryker\Shared\FirstSpiritApi\FirstSpiritApiConstants;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre\PreProcessorInterface;

/**
 * @method \ValanticSpryker\Zed\FirstSpiritApi\Communication\FirstSpiritApiCommunicationFactory getFactory()
 * @method \ValanticSpryker\Zed\FirstSpiritApi\Business\FirstSpiritApiFacadeInterface getFacade()
 */
class ResourceQueryTypePreProcessor implements PreProcessorInterface
{
    /**
     * Extracts the path segment responsible for building the resource action
     *
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer
     */
    public function process(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiRequestTransfer
    {
        $path = $apiRequestTransfer->getPath() ?? '';
        $identifier = $path;
        $delimiterPosition = strpos($path, '/');
        if ($delimiterPosition !== false) {
            $identifier = substr($path, 0, $delimiterPosition);
            $path = substr($path, $delimiterPosition + 1);
        }

        $resourceId = null;
        $identifier = trim($identifier);
        if ($identifier !== '') {
            switch (strtolower($identifier)) {
                case FirstSpiritApiConstants::QUERY_TYPE_IDS:
                    $apiRequestTransfer->setQueryType(FirstSpiritApiConstants::QUERY_TYPE_IDS);

                    break;
                case FirstSpiritApiConstants::QUERY_TYPE_TREE:
                    $apiRequestTransfer->setQueryType(FirstSpiritApiConstants::QUERY_TYPE_TREE);

                    break;
                default:
                    $apiRequestTransfer->setQueryType(FirstSpiritApiConstants::QUERY_TYPE_ID);
                    $resourceId = (int)$identifier;
            }
        }

        $apiRequestTransfer->setResourceId($resourceId);
        $apiRequestTransfer->setPath($path);

        return $apiRequestTransfer;
    }
}
