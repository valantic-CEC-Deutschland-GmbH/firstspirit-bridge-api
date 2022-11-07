<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Communication\EventListener;

use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Generated\Shared\Transfer\FirstSpiritApiResponseTransfer;
use JsonException;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Api\Communication\Controller\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Throwable;
use ValanticSpryker\Zed\FirstSpiritApi\Business\FirstSpiritApiFacadeInterface;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Http\HttpConstants;
use ValanticSpryker\Zed\FirstSpiritApi\Communication\Transformer\TransformerInterface;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;

class FirstSpiritApiControllerEventListener implements FirstSpiritApiControllerEventListenerInterface
{
    use LoggerTrait;

    public const REQUEST_URI = 'REQUEST_URI';

    /**
     * @var \ValanticSpryker\Zed\FirstSpiritApi\Communication\Transformer\TransformerInterface
     */
    private TransformerInterface $transformer;

    /**
     * @var \ValanticSpryker\Zed\FirstSpiritApi\Business\FirstSpiritApiFacadeInterface
     */
    private FirstSpiritApiFacadeInterface $apiFacade;

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    private UtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \ValanticSpryker\Zed\FirstSpiritApi\Communication\Transformer\TransformerInterface $transformer
     * @param \ValanticSpryker\Zed\FirstSpiritApi\Business\FirstSpiritApiFacadeInterface $apiFacade
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        TransformerInterface $transformer,
        FirstSpiritApiFacadeInterface $apiFacade,
        UtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->transformer = $transformer;
        $this->apiFacade = $apiFacade;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $controllerEvent
     *
     * @return void
     */
    public function onKernelControllerEvent(ControllerEvent $controllerEvent): void
    {
        $request = $controllerEvent->getRequest();

        if (
            !$request->server->has(static::REQUEST_URI)
            || !str_starts_with($request->server->get(static::REQUEST_URI), FirstSpiritApiConfig::ROUTE_PREFIX_FIRST_SPIRIT_API_REST)
        ) {
            return;
        }

        /** @var array $currentController */
        $currentController = $controllerEvent->getController();
        [$controller, $action] = $currentController;

        if (!$controller instanceof AbstractApiController) {
            return;
        }

        $request = $controllerEvent->getRequest();
        $apiController = function () use ($controller, $action, $request) {
                return $this->executeControllerAction($request, $controller, $action);
        };

        $controllerEvent->setController($apiController);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Zed\Api\Communication\Controller\AbstractApiController $controller
     * @param mixed $action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function executeControllerAction(Request $request, AbstractApiController $controller, mixed $action): Response
    {
        $apiRequestTransfer = $this->getApiRequestTransfer($request);
        $this->logRequest($apiRequestTransfer);

        try {
            $responseTransfer = $controller->$action($apiRequestTransfer);
        } catch (Throwable $exception) {
            $responseTransfer = new FirstSpiritApiResponseTransfer();
            $responseTransfer->setCode($this->resolveStatusCode((int)$exception->getCode()));
            $responseTransfer->setMessage($exception->getMessage());
            $responseTransfer->setStackTrace(sprintf(
                '%s (%s, line %d): %s',
                get_class($exception),
                $exception->getFile(),
                $exception->getLine(),
                $exception->getTraceAsString(),
            ));
        }

        $this->logResponse($responseTransfer);

        return $this->transformer->transform($apiRequestTransfer, $responseTransfer, new Response());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \JsonException
     *
     * @return \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer
     */
    private function getApiRequestTransfer(Request $request): FirstSpiritApiRequestTransfer
    {
        $requestTransfer = new FirstSpiritApiRequestTransfer();

        $requestTransfer->setRequestType($request->getMethod());
        $requestTransfer->setQueryData($request->query->all());
        $requestTransfer->setHeaderData($request->headers->all());

        $serverData = $request->server->all();
        $requestTransfer->setServerData($serverData);
        $requestTransfer->setRequestUri($serverData[static::REQUEST_URI]);

        if (str_starts_with((string)$request->headers->get(HttpConstants::HEADER_CONTENT_TYPE), 'application/json')) {
            $content = $request->getContent();
            if (is_resource($content)) {
                $content = stream_get_contents($content);
                $content = $content ?: '{}';
            }

            try {
                $data = $this->utilEncodingService->decodeJson($content, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $exception) {
                $this->logRequest($requestTransfer);

                throw $exception;
            }
            $request->request->replace(is_array($data) ? $data : []);
        }

        return $requestTransfer->setRequestData($request->request->all());
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    private function logRequest(FirstSpiritApiRequestTransfer $apiRequestTransfer): void
    {
        $filteredApiRequestTransfer = $this->apiFacade->filterApiRequestTransfer($apiRequestTransfer);

        $this->getLogger()->info(sprintf(
            'API request [%s %s]: %s',
            $apiRequestTransfer->getRequestTypeOrFail(),
            $apiRequestTransfer->getRequestUriOrFail(),
            $this->utilEncodingService->encodeJson($filteredApiRequestTransfer->toArray()),
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer $responseTransfer
     *
     * @return void
     */
    private function logResponse(FirstSpiritApiResponseTransfer $responseTransfer): void
    {
        $responseTransferData = $responseTransfer->toArray();
        unset($responseTransferData['request']);

        $this->getLogger()->info(sprintf(
            'API response [code %s]: %s',
            $responseTransfer->getCodeOrFail(),
            $this->utilEncodingService->encodeJson($responseTransferData),
        ));
    }

    /**
     * @param int $code
     *
     * @return int
     */
    private function resolveStatusCode(int $code): int
    {
        if ($code < FirstSpiritApiConfig::HTTP_CODE_SUCCESS || $code > FirstSpiritApiConfig::HTTP_CODE_INTERNAL_ERROR) {
            return FirstSpiritApiConfig::HTTP_CODE_INTERNAL_ERROR;
        }

        return $code;
    }
}
