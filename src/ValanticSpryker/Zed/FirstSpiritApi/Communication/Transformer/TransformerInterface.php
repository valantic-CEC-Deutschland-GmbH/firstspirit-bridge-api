<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Communication\Transformer;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Generated\Shared\Transfer\FirstSpiritApiResponseTransfer;
use Symfony\Component\HttpFoundation\Response;

interface TransformerInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer $apiResponseTransfer
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function transform(
        FirstSpiritApiRequestTransfer $apiRequestTransfer,
        FirstSpiritApiResponseTransfer $apiResponseTransfer,
        Response $response
    ): Response;

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer $apiResponseTransfer
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param string $message
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function transformBadRequest(FirstSpiritApiResponseTransfer $apiResponseTransfer, Response $response, string $message): Response;
}
