<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductFirstSpiritApi\Communication\Plugin;

use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiDataTransfer;
use Generated\Shared\Transfer\FirstSpiritApiItemTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Generated\Shared\Transfer\FirstSpiritApiResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use ValanticSpryker\Shared\FirstSpiritApi\FirstSpiritApiConstants;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Exception\FirstSpiritApiDispatchingException;
use ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiResourcePluginInterface;
use ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\OptionsForItemInterface;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;

/**
 * @method \ValanticSpryker\Zed\ProductFirstSpiritApi\Business\ProductFirstSpiritApiFacadeInterface getFacade()
 * @method \ValanticSpryker\Zed\ProductFirstSpiritApi\ProductFirstSpiritApiConfig getConfig()
 */
class ProductsFirstSpiritApiResourcePlugin extends AbstractPlugin implements FirstSpiritApiResourcePluginInterface, OptionsForItemInterface
{
    /**
     * @var string
     */
    public const RESOURCE_NAME = 'products';

    /**
     * @return string
     */
    public function getResourceName(): string
    {
        return self::RESOURCE_NAME;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiDataTransfer $apiDataTransfer
     *
     * @throws \ValanticSpryker\Zed\FirstSpiritApi\Business\Exception\FirstSpiritApiDispatchingException
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function add(FirstSpiritApiDataTransfer $apiDataTransfer): FirstSpiritApiItemTransfer
    {
        throw new FirstSpiritApiDispatchingException('Method add not available for ' . self::RESOURCE_NAME);
    }

    /**
     * @param int $id
     *
     * @throws \ValanticSpryker\Zed\FirstSpiritApi\Business\Exception\FirstSpiritApiDispatchingException
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function get(int $id): FirstSpiritApiItemTransfer
    {
        throw new FirstSpiritApiDispatchingException('Method get not available for ' . self::RESOURCE_NAME);
    }

    /**
     * @param int $id
     * @param \Generated\Shared\Transfer\FirstSpiritApiDataTransfer $apiDataTransfer
     *
     * @throws \ValanticSpryker\Zed\FirstSpiritApi\Business\Exception\FirstSpiritApiDispatchingException
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function update(int $id, FirstSpiritApiDataTransfer $apiDataTransfer): FirstSpiritApiItemTransfer
    {
        throw new FirstSpiritApiDispatchingException('Method update not available for ' . self::RESOURCE_NAME);
    }

    /**
     * @param int $id
     *
     * @throws \ValanticSpryker\Zed\FirstSpiritApi\Business\Exception\FirstSpiritApiDispatchingException
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function remove(int $id): FirstSpiritApiItemTransfer
    {
        throw new FirstSpiritApiDispatchingException('Method remove not available for ' . self::RESOURCE_NAME);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer
     */
    public function find(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        if ($apiRequestTransfer->getQueryType() === FirstSpiritApiConstants::QUERY_TYPE_IDS) {
            $result = $this->getFacade()->getProductsByIds($apiRequestTransfer);
        } else {
            $result = $this->getFacade()->getAllProducts($apiRequestTransfer);
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer
     */
    public function head(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiResponseTransfer
    {
        return (new FirstSpiritApiResponseTransfer())
            ->setCode(FirstSpiritApiConfig::HTTP_CODE_SUCCESS);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function getHttpMethodsForItem(array $params): array
    {
        return [
            FirstSpiritApiConfig::HTTP_METHOD_GET,
            FirstSpiritApiConfig::HTTP_METHOD_HEAD,
        ];
    }
}
