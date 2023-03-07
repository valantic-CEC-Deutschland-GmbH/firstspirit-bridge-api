<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Http;

interface HttpConstants
{
    /**
     * @var string
     */
    public const HEADER_CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
    public const HEADER_X_HAS_NEXT = 'X-HasNext';

    /**
     * @var string
     */
    public const HEADER_X_TOTAL = 'X-Total';
}
