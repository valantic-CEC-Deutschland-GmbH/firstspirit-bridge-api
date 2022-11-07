<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Generator;

use Spryker\Service\UtilText\UtilTextServiceInterface;

class UrlGenerator implements UrlGeneratorInterface
{
    private UtilTextServiceInterface $utilTextService;

    /**
     * @param \Spryker\Service\UtilText\UtilTextServiceInterface $utilTextService
     */
    public function __construct(UtilTextServiceInterface $utilTextService)
    {
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param string $label
     *
     * @return string
     */
    public function getGeneratedUrl(string $label): string
    {
        return $this->utilTextService->generateSlug($label);
    }
}
