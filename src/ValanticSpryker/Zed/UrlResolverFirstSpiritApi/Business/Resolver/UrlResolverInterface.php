<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Resolver;

use Generated\Shared\Transfer\UrlResolverFirstSpiritApiAttributeTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;

interface UrlResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return \Generated\Shared\Transfer\UrlResolverFirstSpiritApiAttributeTransfer
     */
    public function resolveUrlAttributes(UrlStorageTransfer $urlStorageTransfer): UrlResolverFirstSpiritApiAttributeTransfer;

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return bool
     */
    public function isApplicable(UrlStorageTransfer $urlStorageTransfer): bool;
}
