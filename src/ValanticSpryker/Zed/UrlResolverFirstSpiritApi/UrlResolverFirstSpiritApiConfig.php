<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\UrlResolverFirstSpiritApi;

use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;

class UrlResolverFirstSpiritApiConfig extends FirstSpiritApiConfig
{
    /**
     * @var string
     */
    public const STORE_PRODUCT_TYPE = 'product';

    /**
     * @var string
     */
    public const STORE_CATEGORY_TYPE = 'category';

    /**
     * @var string
     */
    public const STORE_CONTENT_TYPE = 'content';

    /**
     * @var array
     */
    public const VALID_STOREFRONT_TYPES = [
        self::STORE_PRODUCT_TYPE,
        self::STORE_CATEGORY_TYPE,
        self::STORE_CONTENT_TYPE,
    ];
}
