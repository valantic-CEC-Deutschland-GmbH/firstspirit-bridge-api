<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi;

use ValanticSpryker\Shared\FirstSpiritApi\FirstSpiritApiConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class FirstSpiritApiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var int
     */
    public const FIRST_SPIRIT_DEFAULT_PAGINATION_PAGING_SIZE = 10;

    /**
     * @api
     *
     * @var string
     */
    public const ROUTE_PREFIX_FIRST_SPIRIT_API_REST = '/first-spirit-api/api/';

    /**
     * @api
     *
     * @var string
     */
    public const FORMAT_TYPE = 'json';

    /**
     * @api
     *
     * @var string
     */
    public const ACTION_CREATE = 'add';

    /**
     * @api
     *
     * @var string
     */
    public const ACTION_READ = 'get';

    /**
     * @api
     *
     * @var string
     */
    public const ACTION_UPDATE = 'update';

    /**
     * @api
     *
     * @var string
     */
    public const ACTION_DELETE = 'remove';

    /**
     * @api
     *
     * @var string
     */
    public const ACTION_INDEX = 'find';

    /**
     * @api
     *
     * @var string
     */
    public const ACTION_HEAD = 'head';
    /**
     * @api
     *
     * @var string
     */
    public const ACTION_OPTIONS = 'options';

    /**
     * @api
     *
     * @var string
     */
    public const HTTP_METHOD_OPTIONS = 'OPTIONS';

    /**
     * @api
     *
     * @var string
     */
    public const HTTP_METHOD_GET = 'GET';

    /**
     * @api
     *
     * @var string
     */
    public const HTTP_METHOD_POST = 'POST';

    /**
     * @api
     *
     * @var string
     */
    public const HTTP_METHOD_PATCH = 'PATCH';

    /**
     * @api
     *
     * @var string
     */
    public const HTTP_METHOD_PUT = 'PUT';

    /**
     * @api
     *
     * @var string
     */
    public const HTTP_METHOD_DELETE = 'DELETE';

    /**
     * @api
     *
     * @var string
     */
    public const HTTP_METHOD_HEAD = 'HEAD';

    /**
     * @api
     *
     * @var int
     */
    public const HTTP_CODE_SUCCESS = 200;

    /**
     * @api
     *
     * @var int
     */
    public const HTTP_CODE_CREATED = 201;

    /**
     * @api
     *
     * @var int
     */
    public const HTTP_CODE_NO_CONTENT = 204;

    /**
     * @api
     *
     * @var int
     */
    public const HTTP_CODE_PARTIAL_CONTENT = 206;

    /**
     * @api
     *
     * @var int
     */
    public const HTTP_CODE_BAD_REQUEST = 400;

    /**
     * @api
     *
     * @var int
     */
    public const HTTP_CODE_NOT_FOUND = 404;

    /**
     * @api
     *
     * @var int
     */
    public const HTTP_CODE_NOT_ALLOWED = 405;

    /**
     * @api
     *
     * @var int
     */
    public const HTTP_CODE_VALIDATION_ERRORS = 422;

    /**
     * @api
     *
     * @var int
     */
    public const HTTP_CODE_INTERNAL_ERROR = 500;

    /**
     * @return bool
     */
    public function isApiEnabled(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isApiDebugEnabled(): bool
    {
        return $this->get(FirstSpiritApiConstants::IS_FIRST_SPIRIT_API_DEBUG_ENABLED);
    }

    /**
     * @return array<string>
     */
    public function getHttpMethodsForItem(): array
    {
        return [
            static::HTTP_METHOD_GET,
            static::HTTP_METHOD_PATCH,
            static::HTTP_METHOD_PUT,
            static::HTTP_METHOD_DELETE,
        ];
    }

    /**
     * @return array<string>
     */
    public function getHttpMethodsForCollection(): array
    {
        return [
            static::HTTP_METHOD_GET,
            static::HTTP_METHOD_POST,
        ];
    }

    /**
     * @return int
     */
    public function getPagingSize(): int
    {
        return $this->get(FirstSpiritApiConstants::PAGING_SIZE, self::FIRST_SPIRIT_DEFAULT_PAGINATION_PAGING_SIZE);
    }

    /**
     * @return string
     */
    public function getSpaUrl(): string
    {
        return $this->get(FirstSpiritApiConstants::FIRST_SPIRIT_SPA_URL);
    }
}
