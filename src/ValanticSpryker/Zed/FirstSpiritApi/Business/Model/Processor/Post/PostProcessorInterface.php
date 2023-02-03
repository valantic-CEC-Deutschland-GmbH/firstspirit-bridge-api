<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Model\Processor\Post;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Generated\Shared\Transfer\FirstSpiritApiResponseTransfer;

interface PostProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer
     */
    public function process(
        FirstSpiritApiRequestTransfer $apiRequestTransfer,
        FirstSpiritApiResponseTransfer $apiResponseTransfer
    ): FirstSpiritApiResponseTransfer;
}
