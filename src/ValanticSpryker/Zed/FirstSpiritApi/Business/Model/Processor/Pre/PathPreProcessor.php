<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Pre;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;

/**
 * @method \ValanticSpryker\Zed\FirstSpiritApi\Communication\FirstSpiritApiCommunicationFactory getFactory()
 * @method \ValanticSpryker\Zed\FirstSpiritApi\Business\FirstSpiritApiFacadeInterface getFacade()
 */
class PathPreProcessor implements PreProcessorInterface
{
    /**
     * @var string
     */
    public const SERVER_REQUEST_URI = 'REQUEST_URI';

    /**
     * Maps the DOCUMENT_URI to the path omitting the base part.
     *
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer
     */
    public function process(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiRequestTransfer
    {
        $path = $apiRequestTransfer->getServerData()[static::SERVER_REQUEST_URI];
        $queryStringIndex = strpos($path, '?');
        if ($queryStringIndex) {
            $path = substr($path, 0, $queryStringIndex);
        }

        if (strpos($path, FirstSpiritApiConfig::ROUTE_PREFIX_FIRST_SPIRIT_API_REST) === 0) {
            $path = substr($path, strlen(FirstSpiritApiConfig::ROUTE_PREFIX_FIRST_SPIRIT_API_REST));
        }

        $apiRequestTransfer->setPath($this->urlEncoding($path));

        return $apiRequestTransfer;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function urlEncoding(string $path): string
    {
        return str_replace('%2C', ',', $path);
    }
}
