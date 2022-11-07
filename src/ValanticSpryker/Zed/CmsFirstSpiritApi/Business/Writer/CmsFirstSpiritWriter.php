<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Writer;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\FirstSpiritApiDataTransfer;
use Generated\Shared\Transfer\FirstSpiritApiItemTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Cms\Business\CmsFacadeInterface;
use Spryker\Zed\CmsPageSearch\Business\CmsPageSearchFacadeInterface;
use Spryker\Zed\CmsStorage\Business\CmsStorageFacadeInterface;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Generator\UrlGeneratorInterface;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Business\ResponseHandler\ResourceResponseHandlerInterface;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Validator\CmsFirstSpiritWriterValidatorInterface;
use ValanticSpryker\Zed\CmsFirstSpiritApi\CmsFirstSpiritApiConfig;

class CmsFirstSpiritWriter implements CmsFirstSpiritWriterInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Zed\Cms\Business\CmsFacadeInterface
     */
    private CmsFacadeInterface $cmsFacade;

    /**
     * @var \ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Validator\CmsFirstSpiritWriterValidatorInterface
     */
    private CmsFirstSpiritWriterValidatorInterface $cmsFirstSpiritWriterValidator;

    /**
     * @var \ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Generator\UrlGeneratorInterface
     */
    private UrlGeneratorInterface $urlGenerator;

    /**
     * @var \ValanticSpryker\Zed\CmsFirstSpiritApi\Business\ResponseHandler\ResourceResponseHandlerInterface
     */
    private ResourceResponseHandlerInterface $responseHandler;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    private LocaleFacadeInterface $localeFacade;

    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    private StoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\CmsStorage\Business\CmsStorageFacadeInterface
     */
    private CmsStorageFacadeInterface $cmsStorageFacade;

    /**
     * @var \Spryker\Zed\CmsPageSearch\Business\CmsPageSearchFacadeInterface
     */
    private CmsPageSearchFacadeInterface $cmsPageSearchFacade;

    /**
     * @param \Spryker\Zed\Cms\Business\CmsFacadeInterface $cmsFacade
     * @param \ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Validator\CmsFirstSpiritWriterValidatorInterface $cmsFirstSpiritWriterValidator
     * @param \ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Generator\UrlGeneratorInterface $urlGenerator
     * @param \ValanticSpryker\Zed\CmsFirstSpiritApi\Business\ResponseHandler\ResourceResponseHandlerInterface $responseHandler
     * @param \Spryker\Zed\Locale\Business\LocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\CmsStorage\Business\CmsStorageFacadeInterface $cmsStorageFacade
     * @param \Spryker\Zed\CmsPageSearch\Business\CmsPageSearchFacadeInterface $cmsPageSearchFacade
     */
    public function __construct(
        CmsFacadeInterface $cmsFacade,
        CmsFirstSpiritWriterValidatorInterface $cmsFirstSpiritWriterValidator,
        UrlGeneratorInterface $urlGenerator,
        ResourceResponseHandlerInterface $responseHandler,
        LocaleFacadeInterface $localeFacade,
        StoreFacadeInterface $storeFacade,
        CmsStorageFacadeInterface $cmsStorageFacade,
        CmsPageSearchFacadeInterface $cmsPageSearchFacade
    ) {
        $this->cmsFacade = $cmsFacade;
        $this->cmsFirstSpiritWriterValidator = $cmsFirstSpiritWriterValidator;
        $this->urlGenerator = $urlGenerator;
        $this->responseHandler = $responseHandler;
        $this->localeFacade = $localeFacade;
        $this->storeFacade = $storeFacade;
        $this->cmsStorageFacade = $cmsStorageFacade;
        $this->cmsPageSearchFacade = $cmsPageSearchFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function addCmsPage(FirstSpiritApiDataTransfer $apiDataTransfer): FirstSpiritApiItemTransfer
    {
        $responseTransfer = new FirstSpiritApiItemTransfer();
        $requestBody = $apiDataTransfer->getData();

        $localeName = $this->getLocaleFromRequestParameters($apiDataTransfer->getQueryData());

        $valid = $this->cmsFirstSpiritWriterValidator
            ->validate($requestBody);

        if (!$valid) {
            return $this->responseHandler->setResponseToBadTransfer($responseTransfer);
        }

        $pageTransfer = $this->hydratePageTransfer($requestBody);

        $currentStore = $this->storeFacade->getCurrentStore();
        $storeRelationTransfer = $this->hydrateStoreRelationTransfer($currentStore);
        $pageTransfer->setStoreRelation($storeRelationTransfer);

        $urlName = $this->urlGenerator->getGeneratedUrl($requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_LABEL]);

        $localeId = $this->localeFacade->getLocale($localeName)->getIdLocale();
        $label = $requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_LABEL];
        $pageAttributeArray = $this->hydratePageAttributeTransfer(
            $localeId,
            $urlName,
            $label,
            $localeName,
        );

        $pageTransfer->setPageAttributes($pageAttributeArray);

        try {
            $createdPageId = $this->cmsFacade->createPage($pageTransfer);
            $this->publishCmsPage($createdPageId);
        } catch (Exception $e) {
            $this->getLogger()->critical("First spirit API CMS add page request failed:" . $e->getMessage());

            return $this->responseHandler->setResponseToBadTransfer($responseTransfer);
        }

        return $responseTransfer->setData(['id' => (string)$createdPageId])
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @param \Generated\Shared\Transfer\FirstSpiritApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function updateCmsPage(int $id, FirstSpiritApiDataTransfer $apiDataTransfer): FirstSpiritApiItemTransfer
    {
        $responseTransfer = new FirstSpiritApiItemTransfer();
        $requestBody = $apiDataTransfer->getData();
        $localeName = $this->getLocaleFromRequestParameters($apiDataTransfer->getQueryData());

        $valid = $this->cmsFirstSpiritWriterValidator
            ->validate($requestBody);

        if (!$valid) {
            return $this->responseHandler->setResponseToBadTransfer($responseTransfer);
        }

        $cmsPage = $this->cmsFacade->findCmsPageById($id);

        if ($cmsPage === null) {
            return $this->responseHandler->setResponseToNotFound($responseTransfer);
        }

        $this->updatePageTransfer($cmsPage, $requestBody);

        $pageAttributes = $cmsPage->getPageAttributes();
        $pageMetaAttributes = $cmsPage->getMetaAttributes();
        $pageAttributes = $this->removeUnneededPageAttributesWithoutLocaleOrData($pageAttributes->getArrayCopy(), $localeName);
        $pageMetaAttributes = $this->removeUnneededMetaAttributes($pageMetaAttributes->getArrayCopy(), $localeName);

        $cmsPage->setPageAttributes($pageAttributes);
        $cmsPage->setMetaAttributes($pageMetaAttributes);

        $this->setPageNameInPageAttributes($pageAttributes, $requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_LABEL]);

        if ($pageAttributes->count() === 0) {
            return $this->responseHandler->setResponseToNotFound($responseTransfer);
        }

        try {
            $cmsPage = $this->cmsFacade->updatePage($cmsPage);
            $this->publishCmsPage($cmsPage->getFkPage());
        } catch (Throwable $e) {
            $this->getLogger()->critical("First spirit API CMS update update request failed:" . $e->getMessage());

            return $this->responseHandler->setResponseToInternalError($responseTransfer);
        }

        return $this->responseHandler->setResponseCodeToUpdated($responseTransfer);
    }

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiItemTransfer
     */
    public function deleteCmsPage(int $id): FirstSpiritApiItemTransfer
    {
        $transfer = new FirstSpiritApiItemTransfer();

        try {
            $this->cmsFacade
                ->deletePageById($id);
            $this->unpublishCmsPage($id);
        } catch (Throwable $e) {
            $this->getLogger()->critical("First spirit API delete request failed:" . $e->getMessage());

            return $this->responseHandler->setResponseToInternalError($transfer);
        }

        $transfer->setStatusCode(Response::HTTP_NO_CONTENT);

        return $transfer;
    }

    /**
     * @param array $requestBody
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    private function hydratePageTransfer(array $requestBody): CmsPageTransfer
    {
        $pageTransfer = new CmsPageTransfer();
        $pageTransfer->setFkTemplate(1);
        $pageTransfer->setIsActive($requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_ACTIVE] ?? '0');
        $pageTransfer->setIsSearchable($requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_ACTIVE] ?? '0');
        $pageTransfer->setParentId($requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_PARENT_ID] ?? null);
        $pageTransfer->setNextSiblingId($requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_NEXT_SIBLING_ID] ?? null);
        $pageTransfer->setTemplate($requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_TEMPLATE]);
        $pageTransfer->setFirstSpiritPageUid($requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_UID] ?? null);

        return $pageTransfer;
    }

    /**
     * @param int $localeId
     * @param string $urlName
     * @param string $label
     * @param string $localeName
     *
     * @return \ArrayObject
     */
    private function hydratePageAttributeTransfer(int $localeId, string $urlName, string $label, string $localeName): ArrayObject
    {
        $pageAttributeTransfer = new CmsPageAttributesTransfer();
        $pageAttributeTransfer->setFkLocale($localeId);
        $pageAttributeTransfer->setUrl($urlName);
        $pageAttributeTransfer->setName($label);
        $pageAttributeTransfer->setLocaleName($localeName);

        return new ArrayObject([$pageAttributeTransfer]);
    }

    /**
     * @param \ArrayObject $pageAttributes
     * @param string $label
     *
     * @return void
     */
    private function setPageNameInPageAttributes(ArrayObject $pageAttributes, string $label): void
    {
        /** @var \Generated\Shared\Transfer\CmsPageAttributesTransfer $pageAttribute */
        foreach ($pageAttributes as $pageAttribute) {
            $pageAttribute->setName($label);
        }
    }

    /**
     * @param array $attributeArray
     * @param mixed $localeName
     *
     * @return \ArrayObject
     */
    private function removeUnneededPageAttributesWithoutLocaleOrData(array $attributeArray, mixed $localeName): ArrayObject
    {
        foreach ($attributeArray as $key => $pageAttribute) {
            if ($localeName !== $pageAttribute->getLocaleName() || $pageAttribute->getName() === null) {
                unset($attributeArray[$key]);
            }
        }

        return new ArrayObject($attributeArray);
    }

    /**
     * @param array $attributeArray
     * @param string $localeName
     *
     * @return \ArrayObject
     */
    private function removeUnneededMetaAttributes(array $attributeArray, string $localeName): ArrayObject
    {
        /** @var \Generated\Shared\Transfer\CmsPageMetaAttributesTransfer $pageMetaAttributesTransfer */
        foreach ($attributeArray as $key => $pageMetaAttributesTransfer) {
            if ($pageMetaAttributesTransfer->getLocaleName() !== $localeName) {
                unset($attributeArray[$key]);
            }
        }

        return new ArrayObject($attributeArray);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPage
     * @param array $requestBody
     *
     * @return void
     */
    private function updatePageTransfer(CmsPageTransfer $cmsPage, array $requestBody): void
    {
        $cmsPage->setTemplate($requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_TEMPLATE]);

        if (array_key_exists(CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_NEXT_SIBLING_ID, $requestBody)) {
            $cmsPage->setNextSiblingId($requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_NEXT_SIBLING_ID]);
        }

        if (array_key_exists(CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_PARENT_ID, $requestBody)) {
            $cmsPage->setParentId($requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_PARENT_ID]);
        }

        if (isset($requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_UID])) {
            $cmsPage->setFirstSpiritPageUid($requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_UID]);
        }

        if (isset($requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_ACTIVE])) {
            $cmsPage->setIsActive($requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_ACTIVE]);
            $cmsPage->setIsSearchable($requestBody[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_ACTIVE]);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    private function hydrateStoreRelationTransfer(StoreTransfer $storeTransfer): StoreRelationTransfer
    {
        $storeRelationTransfer = new StoreRelationTransfer();
        $storeRelationTransfer->addIdStores($storeTransfer->getIdStore());
        $storeRelationTransfer->addStores($storeTransfer);

        return $storeRelationTransfer;
    }

    /**
     * @param int $createdPageId
     *
     * @return void
     */
    private function publishCmsPage(int $createdPageId): void
    {
        $this->cmsFacade->publishWithVersion($createdPageId, '1');
        $this->cmsStorageFacade->publish([$createdPageId]);
        $this->cmsPageSearchFacade->publish([$createdPageId]);
    }

    /**
     * @param int $createdPageId
     *
     * @return void
     */
    private function unpublishCmsPage(int $createdPageId): void
    {
        $this->cmsStorageFacade->unpublish([$createdPageId]);
        $this->cmsPageSearchFacade->unpublish([$createdPageId]);
    }

    /**
     * @param array $parameters
     *
     * @return string
     */
    private function getLocaleFromRequestParameters(array $parameters): string
    {
        $currentLocale = $this->localeFacade->getCurrentLocaleName();
        if (!isset($parameters[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_LANGUAGE])) {
            return $currentLocale;
        }

        $lang = $parameters[CmsFirstSpiritApiConfig::FIRST_SPIRIT_PAGE_LANGUAGE];
        $locales = $this->localeFacade->getAvailableLocales();

        return $locales[$lang] ?? $currentLocale;
    }
}
