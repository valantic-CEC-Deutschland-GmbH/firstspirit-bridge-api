<?php


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
