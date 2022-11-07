<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Communication\Router;

use Spryker\Zed\Api\Communication\Controller\AbstractApiController;
use Spryker\Zed\Kernel\ClassResolver\Controller\ControllerResolver;
use Spryker\Zed\Kernel\Communication\BundleControllerAction;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;
use TypeError;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig;

class FirstSpiritApiRouter implements RouterInterface
{
    /**
     * @var string
     */
    protected const MODULE_NAME = 'FirstSpiritApi';

    /**
     * @var string
     */
    protected const CONTROLLER_NAME = 'Rest';

    /**
     * @var string
     */
    protected const ACTION_NAME = 'index';

    /**
     * @var \ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig
     */
    protected $config;

    /**
     * @var \Symfony\Component\Routing\RequestContext
     */
    protected $context;

    /**
     * @param \ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig $config
     */
    public function __construct(FirstSpiritApiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Symfony\Component\Routing\RequestContext $context
     *
     * @return void
     */
    public function setContext(RequestContext $context): void
    {
        $this->context = $context;
    }

    /**
     * @return \Symfony\Component\Routing\RequestContext
     */
    public function getContext(): RequestContext
    {
        return $this->context;
    }

    /**
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function getRouteCollection(): RouteCollection
    {
        return new RouteCollection();
    }

    /**
     * @param string $name
     * @param array $parameters
     * @param int $referenceType
     *
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     *
     * @return string
     */
    public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH): string
    {
        throw new RouteNotFoundException();
    }

    /**
     * @param string $pathinfo
     *
     * @throws \TypeError
     *
     * @return array
     */
    public function match(string $pathinfo): array
    {
        if (!$this->config->isApiEnabled()) {
            return [];
        }

        $this->assertValidPath($pathinfo);

        $controllerResolver = new ControllerResolver();
        $bundleControllerAction = new BundleControllerAction(
            static::MODULE_NAME,
            static::CONTROLLER_NAME,
            static::ACTION_NAME,
        );

        /** @var \Spryker\Zed\Kernel\Communication\Controller\AbstractController $controller */
        $controller = $controllerResolver->resolve($bundleControllerAction);

        if (!$controller instanceof AbstractApiController) {
            $class = get_class($controller);

            throw new TypeError(sprintf('"%s" should be an instance of "%s"', $class, AbstractApiController::class));
        }
        $controller->initialize();

        return [
            '_controller' => [$controller, static::ACTION_NAME . 'Action'],
            '_route' => $this->getRoute(),
            'controller' => static::CONTROLLER_NAME,
            'action' => static::ACTION_NAME,
            'module' => static::MODULE_NAME,
        ];
    }

    /**
     * @param string $path
     *
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     *
     * @return void
     */
    private function assertValidPath(string $path): void
    {
        if (strpos($path, FirstSpiritApiConfig::ROUTE_PREFIX_FIRST_SPIRIT_API_REST) !== 0) {
            throw new ResourceNotFoundException(sprintf(
                'Invalid URI prefix, expected "%s" in path "%s"',
                FirstSpiritApiConfig::ROUTE_PREFIX_FIRST_SPIRIT_API_REST,
                $path,
            ));
        }
    }

    /**
     * @return string
     */
    private function getRoute(): string
    {
        return sprintf('%s/%s/%s', static::MODULE_NAME, static::CONTROLLER_NAME, static::ACTION_NAME);
    }
}
