<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CmsFirstSpiritApi;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsFirstSpiritApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const FIRST_SPIRIT_PAGE_UID = 'pageUid';

    /**
     * @var string
     */
    public const FIRST_SPIRIT_PAGE_TEMPLATE = 'template';

    /**
     * @var string
     */
    public const FIRST_SPIRIT_PAGE_ACTIVE = 'visible';

    /**
     * @var string
     */
    public const FIRST_SPIRIT_PAGE_LABEL = 'label';

    /**
     * @var string
     */
    public const FIRST_SPIRIT_PAGE_PARENT_ID = 'parentId';

    /**
     * @var string
     */
    public const FIRST_SPIRIT_PAGE_NEXT_SIBLING_ID = 'nextSiblingId';

    /**
     * @var string
     */
    public const FIRST_SPIRIT_PAGE_LANGUAGE = 'lang';

    /**
     * @var string
     */
    public const FIRST_SPIRIT_PAGINATION_KEY = 'page';
}
