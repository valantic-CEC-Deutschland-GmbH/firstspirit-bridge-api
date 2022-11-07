<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin;

/**
 * Implement this for your ApiResourcePlugin if you want to overwrite the default methods.
 */
interface OptionsForCollectionInterface
{
    /**
     * @api
     *
     * @param array $params
     *
     * @return array
     */
    public function getHttpMethodsForCollection(array $params): array;
}
