<?php

namespace ValanticSprykerTest\Zed\CmsFirstSpiritApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery;
use Spryker\Zed\Cms\Business\CmsFacade;
use Spryker\Zed\CmsPageSearch\Business\CmsPageSearchFacade;
use Spryker\Zed\CmsStorage\Business\CmsStorageFacade;
use Spryker\Zed\Locale\Business\LocaleFacade;
use ValanticSprykerTest\Zed\CmsFirstSpiritApi\CmsFirstSpiritApiBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group ValanticSprykerTest
 * @group Zed
 * @group CmsFirstSpiritApi
 * @group Business
 * @group CmsFirstSpiritApiTest
 * Add your own group annotations below this line
 * @group FirstSpiritApi
 */
class CmsFirstSpiritApiTest extends Unit
{
    /**
     * @var CmsFirstSpiritApiBusinessTester
     */
    protected CmsFirstSpiritApiBusinessTester $tester;

    /**
     * @var string
     */
    protected const BASE_URL = 'http://backend-api.de.spryker.local/first-spirit-api/api/';

    /**
     * @var string
     */
    protected const RESOURCE = 'contentpages';

    /**
     * @var string
     */
    protected const USERNAME = 'spryker';

    /**
     * @var string
     */
    protected const PASSWORD = 'secret';

    /**
     * @return void
     */
    public function testDoesNotWorkWithoutAuth(): void
    {
        $this->getResponse(auth: false);

        $this->tester->seeResponseCodeIs(401);
    }

    /**
     * @return void
     */
    public function testSuccessfulStatusCodeOnBasicRequest(): void
    {
        $templateModel = SpyCmsTemplateQuery::create()
            ->filterByTemplateName('Placeholders Title & Content')
            ->filterByTemplatePath('@Cms/templates/placeholders-title-content/placeholders-title-content.twig')
            ->findOneOrCreate();

        $templateModel->save();

        $templateId = $templateModel->getIdCmsTemplate();

        $localeFacade = new LocaleFacade();
        $localeName = $localeFacade->getCurrentLocale()->getLocaleName();

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $cmsPageModel = $this->tester->haveCmsPage([
            CmsPageTransfer::FK_TEMPLATE => $templateId,
            CmsPageTransfer::IS_ACTIVE => true,
            CmsPageTransfer::IS_SEARCHABLE => true,
            CmsPageAttributesTransfer::LOCALE_NAME => $localeName,
            CmsPageAttributesTransfer::FK_LOCALE => $localeFacade->getCurrentLocale()->getIdLocale(),
            CmsPageTransfer::STORE_RELATION => [
                StoreRelationTransfer::ID_ENTITY => $storeTransfer->getIdStore(),
                StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()],
                StoreRelationTransfer::STORES => [$storeTransfer],
            ],
        ]);

        $pageId = $cmsPageModel->getFkPage();

        $cmsFacade = new CmsFacade();
        $cmsFacade->publishWithVersion($pageId, '1');

        $cmsPageSearchFacade = new CmsPageSearchFacade();
        $cmsPageSearchFacade->publish([$pageId]);

        $this->tester->getLocator()
            ->cmsStorage()
            ->facade()
            ->publish([$pageId]);

        $this->tester->getLocator()
            ->queue()
            ->facade()
            ->startTask('sync.storage.cms');

        $this->tester->getLocator()
            ->queue()
            ->facade()
            ->startTask('sync.search.cms');

        $params = sprintf('/?lang=%s', $this->getLangParameter($localeName));
        $this->getResponse(params: $params);
        $this->tester->seeResponseCodeIsSuccessful();

        $cmsFacade->deletePageById($pageId);
        (new CmsStorageFacade())->unpublish([$pageId]);
        $cmsPageSearchFacade->unpublish([$pageId]);
    }

    /**
     * @return void
     */
    public function testNotFoundWhenNoPagesArePresent(): void
    {
        $params = sprintf('/?lang=%s', $this->getLangParameter('de_DE'));
        $this->getResponse(params: $params);
        $this->tester->seeResponseCodeIsSuccessful();
    }

    /**
     * @return void
     */
    public function testGetContentPagesByIds(): void
    {
        $templateModel = SpyCmsTemplateQuery::create()
            ->filterByTemplateName('Placeholders Title & Content')
            ->filterByTemplatePath('@Cms/templates/placeholders-title-content/placeholders-title-content.twig')
            ->findOneOrCreate();

        $templateModel->save();

        $templateId = $templateModel->getIdCmsTemplate();

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $localeName = (new LocaleFacade())->getCurrentLocale()->getLocaleName();

        $cmsPageModel = $this->tester->haveCmsPage([
            CmsPageTransfer::FK_TEMPLATE => $templateId,
            CmsPageTransfer::IS_ACTIVE => true,
            CmsPageTransfer::IS_SEARCHABLE => true,
            CmsPageAttributesTransfer::LOCALE_NAME => $localeName,
            CmsPageAttributesTransfer::FK_LOCALE => (new LocaleFacade())->getCurrentLocale()->getIdLocale(),
            CmsPageTransfer::STORE_RELATION => [
                StoreRelationTransfer::ID_ENTITY => $storeTransfer->getIdStore(),
                StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()],
                StoreRelationTransfer::STORES => [$storeTransfer],
            ],
        ]);

        $cmsPageModelSecond = $this->tester->haveCmsPage([
            CmsPageTransfer::FK_TEMPLATE => $templateId,
            CmsPageTransfer::IS_ACTIVE => true,
            CmsPageTransfer::IS_SEARCHABLE => true,
            CmsPageAttributesTransfer::LOCALE_NAME => $localeName,
            CmsPageAttributesTransfer::FK_LOCALE => (new LocaleFacade())->getCurrentLocale()->getIdLocale(),
            CmsPageTransfer::STORE_RELATION => [
                StoreRelationTransfer::ID_ENTITY => $storeTransfer->getIdStore(),
                StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()],
                StoreRelationTransfer::STORES => [$storeTransfer],
            ],
        ]);

        $firstId = $cmsPageModel->getFkPage();
        $secondId = $cmsPageModelSecond->getFkPage();
        $cmsFacade = new CmsFacade();

        $cmsFacade->publishWithVersion($firstId, '1');
        $cmsFacade->publishWithVersion($secondId, '1');

        $this->tester->getLocator()
            ->cmsStorage()
            ->facade()
            ->publish([$firstId, $secondId]);

        $this->tester->getLocator()
            ->queue()
            ->facade()
            ->startTask('sync.storage.cms');

        $params = sprintf('/ids/%s,%s?lang=%s', $firstId, $secondId, $this->getLangParameter($localeName));

        $this->getResponse(params: $params);
        $this->tester->seeResponseCodeIsSuccessful();

        (new CmsFacade())->deletePageById($firstId);
        (new CmsFacade())->deletePageById($secondId);
        (new CmsStorageFacade())->unpublish([$firstId, $secondId]);
    }

    /**
     * @return void
     */
    public function testGetNotFoundLinkOnNonExistentId(): void
    {
        $params = sprintf('/ids/%s', 213131);

        $this->getResponse(params: $params);
        $this->tester->seeResponseCodeIs(404);
    }

    /**
     * @return void
     */
    public function testDeleteContentPage(): void
    {
        $templateModel = SpyCmsTemplateQuery::create()
            ->filterByTemplateName('Placeholders Title & Content')
            ->filterByTemplatePath('@Cms/templates/placeholders-title-content/placeholders-title-content.twig')
            ->findOneOrCreate();

        $templateModel->save();

        $templateId = $templateModel->getIdCmsTemplate();

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $localeName = (new LocaleFacade())->getCurrentLocale()->getLocaleName();
        $cmsPageModel = $this->tester->haveCmsPage([
            CmsPageTransfer::FK_TEMPLATE => $templateId,
            CmsPageTransfer::IS_ACTIVE => true,
            CmsPageTransfer::IS_SEARCHABLE => true,
            CmsPageAttributesTransfer::LOCALE_NAME => $localeName,
            CmsPageAttributesTransfer::FK_LOCALE => (new LocaleFacade())->getCurrentLocale()->getIdLocale(),
            CmsPageTransfer::STORE_RELATION => [
                StoreRelationTransfer::ID_ENTITY => $storeTransfer->getIdStore(),
                StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()],
                StoreRelationTransfer::STORES => [$storeTransfer],
            ],
        ]);

        $pageId = $cmsPageModel->getFkPage();

        $cmsFacade = new CmsFacade();
        $cmsFacade->publishWithVersion($pageId, '1');

        $this->tester->getLocator()
            ->cmsStorage()
            ->facade()
            ->publish([$pageId]);

        $this->tester->getLocator()
            ->queue()
            ->facade()
            ->startTask('sync.storage.cms');

        $params = sprintf('/%s?lang=%s', $pageId, $this->getLangParameter($localeName));
        $this->getResponse(params: $params, method: 'DELETE');

        $this->tester->seeResponseCodeIsSuccessful();

        $this->getResponse($params);

        $this->tester->seeResponseCodeIs(404);

        (new CmsFacade())->deletePageById($pageId);
        (new CmsStorageFacade())->unpublish([$pageId]);
    }

    /**
     * @return void
     */
    public function testHeadContentPage(): void
    {
        $this->getResponse(method: 'HEAD');
        $this->tester->seeResponseCodeIsSuccessful();
    }

    /**
     * @return void
     */
    public function testCreateContentPageWithEnglishLocale(): void
    {
        $locale = 'en_US';

        $body = [
            'pageUid' => random_int(1, 10000),
            'template' => str_shuffle('asdadsaqiowdjqodqasdxz#2131'),
            'visible' => '1',
            'label' => str_shuffle('q2131k3091jWQWQ#@131'),
            'parentId' => NULL,
            'nextSiblingId' => NULL,
        ];

        $params = sprintf('?lang=%s', $this->getLangParameter($locale));

        $response = $this->getResponse(params: $params, method: 'POST', body: $body);
        $this->tester->seeResponseCodeIsSuccessful();

        $response = (array)json_decode($response, true);

        $id = $response['id'];

        $params = sprintf('/ids/%s?lang=%s', $id, $this->getLangParameter($locale));

        $this->tester->getLocator()
            ->queue()
            ->facade()
            ->startTask('sync.storage.cms');

        $response = $this->getResponse(params: $params);
        $response = (array)json_decode($response, true);

        $this->assertSame($body['label'], $response[0]['label']);

        $this->tester->seeResponseCodeIsSuccessful();

        (new CmsFacade())->deletePageById($id);
        (new CmsStorageFacade())->unpublish([$id]);
    }

    /**
     * @return void
     */
    public function testCreateContentPageWithGermanLocale(): void
    {

        $locale = 'de_DE';
        $body = [
            'pageUid' => random_int(1, 10000),
            'template' => str_shuffle('asdadsaqiowdjqodqasdxz#2131'),
            'visible' => '1',
            'label' => str_shuffle('q2131k3091jWQWQ#@131'),
            'parentId' => NULL,
            'nextSiblingId' => NULL,
        ];


        $params = sprintf('?lang=%s', $this->getLangParameter($locale));

        $response = $this->getResponse(params: $params, method: 'POST', body: $body);
        $this->tester->seeResponseCodeIsSuccessful();

        $response = (array)json_decode($response, true);

        $id = $response['id'];

        $params = sprintf('/ids/%s?lang=%s', $id, $this->getLangParameter($locale));

        $this->tester->getLocator()
            ->queue()
            ->facade()
            ->startTask('sync.storage.cms');

        $response = $this->getResponse(params: $params);
        $response = (array)json_decode($response, true);

        $this->assertSame($body['label'], $response[0]['label']);


        $this->tester->seeResponseCodeIsSuccessful();

        (new CmsFacade())->deletePageById($id);
        (new CmsStorageFacade())->unpublish([$id]);
    }

    /**
     * @return void
     */
    public function testCreateCmsPageIsReturningBadRequestWhenTemplateIsMissing(): void
    {
        // template is missing which is required
        $body = [
            'pageUid' => random_int(1, 10000),
            'visible' => '1',
            'label' => str_shuffle('q2131k3091jWQWQ#@131'),
            'parentId' => NULL,
            'nextSiblingId' => NULL,
        ];

        $this->getResponse(method: 'POST', body: $body);
        $this->tester->seeResponseCodeIs(400);
    }

    /**
     * @return void
     */
    public function testCreateCmsPageIsReturningBadRequestWhenLabelIsMissing(): void
    {
        // template is missing which is required
        $body = [
            'pageUid' => random_int(1, 10000),
            'template' => str_shuffle('asdadsaqiowdjqodqasdxz#2131'),
            'visible' => '1',
            'parentId' => NULL,
            'nextSiblingId' => NULL,
        ];

        $this->getResponse(method: 'POST', body: $body);
        $this->tester->seeResponseCodeIs(400);
    }

    /**
     * @return void
     */
    public function testCreateCmsPageIsReturningBadRequestWhenTemplateAndLabelAreMissing(): void
    {
        // template is missing which is required
        $body = [
            'pageUid' => random_int(1, 10000),
            'visible' => '1',
            'parentId' => NULL,
            'nextSiblingId' => NULL,
        ];

        $this->getResponse(method: 'POST', body: $body);
        $this->tester->seeResponseCodeIs(400);
    }

    /**
     * @return void
     */
    public function testUpdateContentPage(): void
    {
        $templateModel = SpyCmsTemplateQuery::create()
            ->filterByTemplateName('Placeholders Title & Content')
            ->filterByTemplatePath('@Cms/templates/placeholders-title-content/placeholders-title-content.twig')
            ->findOneOrCreate();

        $templateModel->save();

        $templateId = $templateModel->getIdCmsTemplate();

        $localeName = (new LocaleFacade())->getCurrentLocale()->getLocaleName();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $cmsPageModel = $this->tester->haveCmsPage([
            CmsPageTransfer::FK_TEMPLATE => $templateId,
            CmsPageTransfer::IS_ACTIVE => false,
            CmsPageTransfer::IS_SEARCHABLE => false,
            CmsPageAttributesTransfer::LOCALE_NAME => $localeName,
            CmsPageAttributesTransfer::FK_LOCALE => (new LocaleFacade())->getCurrentLocale()->getIdLocale(),
            CmsPageTransfer::STORE_RELATION => [
                StoreRelationTransfer::ID_ENTITY => $storeTransfer->getIdStore(),
                StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()],
                StoreRelationTransfer::STORES => [$storeTransfer],
            ],
        ]);

        $pageId = $cmsPageModel->getFkPage();

        $params = sprintf('/%s?lang=%s', $pageId, $this->getLangParameter($localeName));
        $body = [
            'pageUid' => random_int(1, 10000),
            'template' => str_shuffle('asdadsaqiowdjqodqasdxz#2131'),
            'visible' => '1',
            'label' => str_shuffle('q2131k3091jWQWQ#@131'),
            'parentId' => NULL,
            'nextSiblingId' => NULL,
        ];

        $this->getResponse(params: $params, method: 'PUT', body: $body);

        $this->tester->getLocator()
            ->queue()
            ->facade()
            ->startTask('sync.storage.cms');

        $this->tester->seeResponseCodeIs(201);

        $params = sprintf('/ids/%s?lang=%s', $pageId, $this->getLangParameter($localeName));
        $response = $this->getResponse(params: $params);

        $response = json_decode($response, true);
        $receivedLabel = $response[0]['label'];

        $this->assertSame($body['label'], $receivedLabel);

        $spyCmsPage = (SpyCmsPageQuery::create()->findOneByIdCmsPage($pageId));
        $this->assertTrue($spyCmsPage->getIsActive());
        $this->assertTrue($spyCmsPage->getIsSearchable());

        $this->tester->seeResponseCodeIs(200);

        (new CmsFacade())->deletePageById($pageId);
        (new CmsStorageFacade())->unpublish([$pageId]);
    }

    /**
     * @return void
     */
    public function testUpdatePageReturningNotFoundNonExistentData(): void
    {
        $params = sprintf('/%s?lang=%s', '9999999', $this->getLangParameter('de_DE'));
        $body = [
            'pageUid' => random_int(1, 10000),
            'template' => str_shuffle('asdadsaqiowdjqodqasdxz#2131'),
            'visible' => '1',
            'label' => str_shuffle('q2131k3091jWQWQ#@131'),
            'parentId' => NULL,
            'nextSiblingId' => NULL,
        ];

        $this->getResponse(params: $params, method: 'PUT', body: $body);

        $this->tester->seeResponseCodeIs(404);
    }

    /**
     * @return void
     */
    public function testUpdatePageReturningBadRequestWhenMissingLabel(): void
    {
        $params = sprintf('/%s?lang=%s', '9999999', $this->getLangParameter('de_DE'));
        // missing label which is required
        $body = [
            'pageUid' => random_int(1, 10000),
            'template' => str_shuffle('asdadsaqiowdjqodqasdxz#2131'),
            'visible' => '1',
            'parentId' => NULL,
            'nextSiblingId' => NULL,
        ];

        $this->getResponse(params: $params, method: 'PUT', body: $body);

        $this->tester->seeResponseCodeIs(400);
    }

    /**
     * @return void
     */
    public function testUpdatePageReturningBadRequestWhenMissingTemplate(): void
    {
        $params = sprintf('/%s?lang=%s', '9999999', $this->getLangParameter('de_DE'));
        // missing label which is required
        $body = [
            'pageUid' => random_int(1, 10000),
            'visible' => '1',
            'label' => str_shuffle('q2131k3091jWQWQ#@131'),
            'parentId' => NULL,
            'nextSiblingId' => NULL,
        ];

        $this->getResponse(params: $params, method: 'PUT', body: $body);

        $this->tester->seeResponseCodeIs(400);
    }

    /**
     * @return void
     */
    public function testUpdatePageReturningBadRequestWhenMissingTemplateAndLabel(): void
    {
        $params = sprintf('/%s?lang=%s', '9999999', $this->getLangParameter('de_DE'));
        // missing label which is required
        $body = [
            'pageUid' => random_int(1, 10000),
            'visible' => '1',
            'parentId' => NULL,
            'nextSiblingId' => NULL,
        ];

        $this->getResponse(params: $params, method: 'PUT', body: $body);

        $this->tester->seeResponseCodeIs(400);
    }

    /**
     * @param string $params
     * @param bool $auth
     * @param mixed $query
     * @param string $method
     * @param array $body
     *
     * @return string
     */
    private function getResponse(string $params = '', bool $auth = true, mixed $query = [], string $method = 'GET', array $body = []): string
    {
        if ($auth) {
            $this->tester->amHttpAuthenticated(self::USERNAME, self::PASSWORD);
        }

        if ($method === 'DELETE') {
            return $this->tester->sendDelete(self::BASE_URL . self::RESOURCE . $params);
        }

        if ($method === 'GET') {
            return $this->tester->sendGet(self::BASE_URL . self::RESOURCE . $params);
        }

        if ($method === 'PUT') {
            return $this->tester->sendPut(self::BASE_URL . self::RESOURCE . $params, $body);
        }

        if ($method === 'HEAD') {
            return $this->tester->sendHead(self::BASE_URL . self::RESOURCE . $params);
        }

        if ($method === 'POST') {
            return $this->tester->sendPost(self::BASE_URL . self::RESOURCE . $params, $body);
        }

        return 'no method hit';
    }

    /**
     * @param string $localeName
     *
     * @return string
     */
    private function getLangParameter(string $localeName): string
    {
        return explode('_', $localeName)[0];
    }
}
