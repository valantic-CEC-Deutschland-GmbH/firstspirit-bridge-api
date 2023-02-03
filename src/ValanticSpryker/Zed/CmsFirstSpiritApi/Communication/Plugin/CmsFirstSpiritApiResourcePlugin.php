<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CmsFirstSpiritApi\Communication\Plugin;

use Generated\Shared\Transfer\FirstSpiritApiCollectionTransfer;
use Generated\Shared\Transfer\FirstSpiritApiDataTransfer;
use Generated\Shared\Transfer\FirstSpiritApiItemTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Generated\Shared\Transfer\FirstSpiritApiResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Exception\FirstSpiritApiDispatchingException;
use ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiResourcePluginInterface;
use ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\OptionsForItemInterface;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;

/**
 * @method \ValanticSpryker\Zed\CmsFirstSpiritApi\Business\CmsFirstSpiritApiFacadeInterface getFacade()
 * @method \ValanticSpryker\Zed\CmsFirstSpiritApi\Business\CmsFirstSpiritApiBusinessFactory getFactory()
 * @method \ValanticSpryker\Zed\CmsFirstSpiritApi\CmsFirstSpiritApiConfig getConfig()
 */
class CmsFirstSpiritApiResourcePlugin extends AbstractPlugin implements FirstSpiritApiResourcePluginInterface, OptionsForItemInterface
{
    /**
     * @var string
     */
    public const RESOURCE_NAME = 'contentpages';

    /**
     * @return string
     */
    public function getResourceName(): string
    {
        return static::RESOURCE_NAME;
    }

    /**
     * @inheritDoc
     */
    public function add(FirstSpiritApiDataTransfer $apiDataTransfer): FirstSpiritApiItemTransfer
    {
        return $this->getFacade()
            ->addCmsPage($apiDataTransfer);
    }

    /**
     * @inheritDoc
     *
     * @throws \ValanticSpryker\Zed\FirstSpiritApi\Business\Exception\FirstSpiritApiDispatchingException
     */
    public function get(int $id): FirstSpiritApiItemTransfer
    {
        throw new FirstSpiritApiDispatchingException('Method GET not available for ' . self::RESOURCE_NAME);
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, FirstSpiritApiDataTransfer $apiDataTransfer): FirstSpiritApiItemTransfer
    {
        return $this->getFacade()
            ->updateCmsPage($id, $apiDataTransfer);
    }

    /**
     * @inheritDoc
     */
    public function remove(int $id): FirstSpiritApiItemTransfer
    {
        return $this->getFacade()
            ->deleteCmsPage($id);
    }

    /**
     * @inheritDoc
     */
    public function find(FirstSpiritApiRequestTransfer $apiRequestTransfer): FirstSpiritApiCollectionTransfer
    {
        return $this->getFacade()
           ->getCmsPagesByIds($apiRequestTransfer);
    }

    /**
     * @inheritDoc
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
            FirstSpiritApiConfig::HTTP_METHOD_POST,
            FirstSpiritApiConfig::HTTP_METHOD_HEAD,
            FirstSpiritApiConfig::HTTP_METHOD_PUT,
        ];
    }
}
