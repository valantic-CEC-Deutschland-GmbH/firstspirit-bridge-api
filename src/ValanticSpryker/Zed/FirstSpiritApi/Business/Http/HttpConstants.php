<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

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
