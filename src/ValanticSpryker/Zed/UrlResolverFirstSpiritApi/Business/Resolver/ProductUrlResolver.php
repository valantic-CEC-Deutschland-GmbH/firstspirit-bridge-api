<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Business\Resolver;

use Generated\Shared\Transfer\UrlResolverFirstSpiritApiAttributeTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\UrlResolverFirstSpiritApiConfig;

class ProductUrlResolver implements UrlResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return \Generated\Shared\Transfer\UrlResolverFirstSpiritApiAttributeTransfer
     */
    public function resolveUrlAttributes(UrlStorageTransfer $urlStorageTransfer): UrlResolverFirstSpiritApiAttributeTransfer
    {
        $type = UrlResolverFirstSpiritApiConfig::STORE_PRODUCT_TYPE;
        $locale = $urlStorageTransfer->getLocaleName();
        $id = $urlStorageTransfer->getFkResourceProductAbstract();
        $transfer = new UrlResolverFirstSpiritApiAttributeTransfer();

        $transfer->setType($type);
        $transfer->setLang($locale);
        $transfer->setId($id);

        return $transfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return bool
     */
    public function isApplicable(UrlStorageTransfer $urlStorageTransfer): bool
    {
        return $urlStorageTransfer->getFkResourceProductAbstract() !== null;
    }
}
