<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Generator;

interface UrlGeneratorInterface
{
    /**
     * @param string $label
     *
     * @return string
     */
    public function getGeneratedUrl(string $label): string;
}
