<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Resolver;

use Generated\Shared\Transfer\UrlResolverFirstSpiritApiAttributeTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\UrlResolverFirstSpiritApiConfig;

class CmsPageUrlResolver implements UrlResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return \Generated\Shared\Transfer\UrlResolverFirstSpiritApiAttributeTransfer
     */
    public function resolveUrlAttributes(UrlStorageTransfer $urlStorageTransfer): UrlResolverFirstSpiritApiAttributeTransfer
    {
        $type = UrlResolverFirstSpiritApiConfig::STORE_CONTENT_TYPE;
        $id = $urlStorageTransfer->getFkResourcePage();
        $locale = $urlStorageTransfer->getLocaleName();

        $transfer = new UrlResolverFirstSpiritApiAttributeTransfer();

        $transfer->setId($id);
        $transfer->setLang($locale);
        $transfer->setType($type);

        return $transfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return bool
     */
    public function isApplicable(UrlStorageTransfer $urlStorageTransfer): bool
    {
        return $urlStorageTransfer->getFkResourcePage() !== null;
    }
}
