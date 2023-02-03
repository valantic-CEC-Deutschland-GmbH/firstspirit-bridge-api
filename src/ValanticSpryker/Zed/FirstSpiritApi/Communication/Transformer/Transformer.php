<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Communication\Transformer;

use Generated\Shared\Transfer\FirstSpiritApiOptionsTransfer;
use Generated\Shared\Transfer\FirstSpiritApiRequestTransfer;
use Generated\Shared\Transfer\FirstSpiritApiResponseTransfer;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Api\Communication\Resolver\FormatterResolverInterface;
use Symfony\Component\HttpFoundation\Response;
use ValanticSpryker\Zed\FirstSpiritApi\Business\Http\HttpConstants;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;

class Transformer implements TransformerInterface
{
    /**
     * @var \Spryker\Zed\Api\Communication\Resolver\FormatterResolverInterface
     */
    private FormatterResolverInterface $formatterResolver;

    /**
     * @var \ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig
     */
    private FirstSpiritApiConfig $apiConfig;

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    private UtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Zed\Api\Communication\Resolver\FormatterResolverInterface $formatterResolver
     * @param \ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig $apiConfig
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        FormatterResolverInterface $formatterResolver,
        FirstSpiritApiConfig $apiConfig,
        UtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->formatterResolver = $formatterResolver;
        $this->apiConfig = $apiConfig;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer $apiResponseTransfer
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function transform(
        FirstSpiritApiRequestTransfer $apiRequestTransfer,
        FirstSpiritApiResponseTransfer $apiResponseTransfer,
        Response $response
    ): Response {
        $headers = $apiResponseTransfer->getHeaders() + $this->getDefaultResponseHeaders($apiRequestTransfer);
        $headers = $this->addPaginationHeadersIfRequired($headers, $apiResponseTransfer);
        $response->headers->add($headers);

        $response->setStatusCode($apiResponseTransfer->getCodeOrFail());

        return $this->addResponseContent($apiRequestTransfer, $apiResponseTransfer, $response);
    }

    /**
     * @inheritDoc
     */
    public function transformBadRequest(FirstSpiritApiResponseTransfer $apiResponseTransfer, Response $response, string $message): Response
    {
        $headers = $apiResponseTransfer->getHeaders() + $this->getDefaultResponseHeaders();
        $response->headers->add($headers);

        $response->setStatusCode(FirstSpiritApiConfig::HTTP_CODE_BAD_REQUEST);
        $response->setContent($this->utilEncodingService->encodeJson([
            'code' => FirstSpiritApiConfig::HTTP_CODE_BAD_REQUEST,
            'message' => $message,
        ]));

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer|null $apiRequestTransfer
     *
     * @return array<string, string>
     */
    protected function getDefaultResponseHeaders(?FirstSpiritApiRequestTransfer $apiRequestTransfer = null): array
    {
        return [
            HttpConstants::HEADER_CONTENT_TYPE => $this->createContentTypeHeader($apiRequestTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer $apiResponseTransfer
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function addResponseContent(
        FirstSpiritApiRequestTransfer $apiRequestTransfer,
        FirstSpiritApiResponseTransfer $apiResponseTransfer,
        Response $response
    ): Response {
        if ($this->isContentless($apiResponseTransfer)) {
            return $response;
        }

        $content = [];
        $content['code'] = $apiResponseTransfer->getCode();
        $content['message'] = $apiResponseTransfer->getMessage();
        if ((int)$apiResponseTransfer->getCode() === FirstSpiritApiConfig::HTTP_CODE_VALIDATION_ERRORS) {
            $content = $this->addValidationErrorsToResponseContent($apiResponseTransfer, $content);
        }

        $result = $apiResponseTransfer->getData();
        if ($result !== null) {
            $content['data'] = $result;
        }

        $meta = $apiResponseTransfer->getMeta();
        if ($meta) {
            $content['links'] = $meta->getLinks();
            if ($meta->getSelf()) {
                $content['links']['self'] = $meta->getSelf();
            }
            $content['meta'] = $meta->getData();
        }

        if ($this->apiConfig->isApiDebugEnabled()) {
            $content['_stackTrace'] = $apiResponseTransfer->getStackTrace();
            $content['_request'] = $apiRequestTransfer->toArray();
        } else {
            $content = $result;
        }

        $content = $this->formatterResolver
            ->resolveFormatter($apiRequestTransfer->getFormatType())
            ->format($content);
        $response->setContent($content);

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiRequestTransfer|null $apiRequestTransfer
     *
     * @return string
     */
    private function createContentTypeHeader(?FirstSpiritApiRequestTransfer $apiRequestTransfer): string
    {
        $formatType = $apiRequestTransfer && $apiRequestTransfer->getFormatType() ? $apiRequestTransfer->getFormatType() : 'json';

        return sprintf('application/%s', $formatType);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer $apiResponseTransfer
     *
     * @return bool
     */
    private function isContentless(FirstSpiritApiResponseTransfer $apiResponseTransfer): bool
    {
        return (int)$apiResponseTransfer->getCode() === FirstSpiritApiConfig::HTTP_CODE_NO_CONTENT || $apiResponseTransfer->getType() === FirstSpiritApiOptionsTransfer::class;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer $apiResponseTransfer
     * @param array $content
     *
     * @return array
     */
    private function addValidationErrorsToResponseContent(
        FirstSpiritApiResponseTransfer $apiResponseTransfer,
        array $content
    ): array {
        foreach ($apiResponseTransfer->getValidationErrors() as $apiValidationErrorTransfer) {
            $field = $this->formatApiValidationField($apiValidationErrorTransfer->getFieldOrFail());
            $content['errors'][$field] = $apiValidationErrorTransfer->getMessages();
        }

        return $content;
    }

    /**
     * @param string|null $field
     *
     * @return string
     */
    private function formatApiValidationField(?string $field): string
    {
        $field = str_replace('][', '.', $field);
        $field = trim($field, '[]');

        return $field;
    }

    /**
     * @param array $headers
     * @param \Generated\Shared\Transfer\FirstSpiritApiResponseTransfer $apiResponseTransfer
     *
     * @return array
     */
    private function addPaginationHeadersIfRequired(array $headers, FirstSpiritApiResponseTransfer $apiResponseTransfer): array
    {
        $pagination = $apiResponseTransfer->getPagination();
        if ($pagination !== null) {
            $total = $pagination->getTotal();
            $page = $pagination->getPage();
            $pageSize = $pagination->getItemsPerPage();
            $hasNext = false;
            if ($total > $page * $pageSize) {
                $hasNext = true;
            }
            $headers[HttpConstants::HEADER_X_HAS_NEXT] = $hasNext;
            $headers[HttpConstants::HEADER_X_TOTAL] = $total;
        }

        return $headers;
    }
}
