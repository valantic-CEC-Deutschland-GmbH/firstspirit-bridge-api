<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\CmsFirstSpiritApi\Business;

use Spryker\Client\CmsPageSearch\CmsPageSearchClientInterface;
use Spryker\Client\CmsStorage\CmsStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Service\UtilText\UtilTextServiceInterface;
use Spryker\Zed\Cms\Business\CmsFacadeInterface;
use Spryker\Zed\CmsPageSearch\Business\CmsPageSearchFacadeInterface;
use Spryker\Zed\CmsStorage\Business\CmsStorageFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Generator\UrlGenerator;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Generator\UrlGeneratorInterface;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Reader\CmsFirstSpiritReader;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Reader\CmsFirstSpiritReaderInterface;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Business\ResponseHandler\ResourceResponseHandler;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Business\ResponseHandler\ResourceResponseHandlerInterface;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Validator\CmsFirstSpiritWriterValidator;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Validator\CmsFirstSpiritWriterValidatorInterface;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Writer\CmsFirstSpiritWriter;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Writer\CmsFirstSpiritWriterInterface;
use ValanticSpryker\Zed\CmsFirstSpiritApi\CmsFirstSpiritApiDependencyProvider;

/**
 * @method \ValanticSpryker\Zed\CmsFirstSpiritApi\CmsFirstSpiritApiConfig getConfig()
 */
class CmsFirstSpiritApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Writer\CmsFirstSpiritWriterInterface
     */
    public function createCmsFirstSpiritWriter(): CmsFirstSpiritWriterInterface
    {
        return new CmsFirstSpiritWriter(
            $this->getCmsFacade(),
            $this->createCmsFirstSpiritValidator(),
            $this->createUrlGenerator(),
            $this->createResourceResponseHandler(),
            $this->getLocaleFacade(),
            $this->getStoreFacade(),
            $this->getCmsStorageFacade(),
            $this->getCmsPageSearchFacade(),
        );
    }

    /**
     * @return \ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Reader\CmsFirstSpiritReaderInterface
     */
    public function createCmsFirstSpiritReader(): CmsFirstSpiritReaderInterface
    {
        return new CmsFirstSpiritReader(
            $this->getCmsStorageClient(),
            $this->getStoreClient(),
            $this->getCmsPageSearchClient(),
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Cms\Business\CmsFacadeInterface
     */
    public function getCmsFacade(): CmsFacadeInterface
    {
        return $this->getProvidedDependency(CmsFirstSpiritApiDependencyProvider::CMS_FACADE);
    }

    /**
     * @return \Spryker\Client\CmsStorage\CmsStorageClientInterface
     */
    public function getCmsStorageClient(): CmsStorageClientInterface
    {
        return $this->getProvidedDependency(CmsFirstSpiritApiDependencyProvider::CMS_STORAGE_CLIENT);
    }

    /**
     * @return \Spryker\Zed\CmsStorage\Business\CmsStorageFacadeInterface
     */
    public function getCmsStorageFacade(): CmsStorageFacadeInterface
    {
        return $this->getProvidedDependency(CmsFirstSpiritApiDependencyProvider::CMS_STORAGE_FACADE);
    }

    /**
     * @return \Spryker\Zed\CmsPageSearch\Business\CmsPageSearchFacadeInterface
     */
    public function getCmsPageSearchFacade(): CmsPageSearchFacadeInterface
    {
        return $this->getProvidedDependency(CmsFirstSpiritApiDependencyProvider::CMS_PAGE_SEARCH_FACADE);
    }

    /**
     * @return \ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Validator\CmsFirstSpiritWriterValidatorInterface
     */
    public function createCmsFirstSpiritValidator(): CmsFirstSpiritWriterValidatorInterface
    {
        return new CmsFirstSpiritWriterValidator();
    }

    /**
     * @return \Spryker\Client\Store\StoreClientInterface
     */
    private function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(CmsFirstSpiritApiDependencyProvider::STORE_CLIENT);
    }

    /**
     * @return \Spryker\Client\CmsPageSearch\CmsPageSearchClientInterface
     */
    public function getCmsPageSearchClient(): CmsPageSearchClientInterface
    {
        return $this->getProvidedDependency(CmsFirstSpiritApiDependencyProvider::CMS_PAGE_SEARCH_CLIENT);
    }

    /**
     * @return \Spryker\Service\UtilText\UtilTextServiceInterface
     */
    public function getUtilTextService(): UtilTextServiceInterface
    {
        return $this->getProvidedDependency(CmsFirstSpiritApiDependencyProvider::UTIL_TEXT_SERVICE);
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    public function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getProvidedDependency(CmsFirstSpiritApiDependencyProvider::LOCALE_FACADE);
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    public function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(CmsFirstSpiritApiDependencyProvider::STORE_FACADE);
    }

    /**
     * @return \ValanticSpryker\Zed\CmsFirstSpiritApi\Business\Generator\UrlGeneratorInterface
     */
    public function createUrlGenerator(): UrlGeneratorInterface
    {
        return new UrlGenerator(
            $this->getUtilTextService(),
        );
    }

    /**
     * @return \ValanticSpryker\Zed\CmsFirstSpiritApi\Business\ResponseHandler\ResourceResponseHandlerInterface
     */
    public function createResourceResponseHandler(): ResourceResponseHandlerInterface
    {
        return new ResourceResponseHandler();
    }
}
