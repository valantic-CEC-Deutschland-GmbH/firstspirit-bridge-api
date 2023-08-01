# firstspirit-bridge-api

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.0-8892BF.svg)](https://php.net/)

# Description
- Provides backend api for FS Bridge API

## Implementation:

1. Install dependency
```
composer require valantic-spryker-eco/firstspirit-bridge-api
```

2. Add configuration to `config_default.php`
```php
$firstSpiritSpaHost = 'www.frontend.de';
$config[FirstSpiritApiConstants::IS_FIRST_SPIRIT_API_DEBUG_ENABLED] = false;
$config[FirstSpiritApiConstants::PAGING_SIZE] = 10;
$config[FirstSpiritApiConstants::FIRST_SPIRIT_SPA_URL] = ($backofficePort === 80 ? 'http://' : 'https://') . $firstSpiritSpaHost;
```

3. Register DispatcherPlugin
```php
<?php

namespace Pyz\Zed\EventDispatcher;

use Spryker\Zed\EventDispatcher\EventDispatcherDependencyProvider as SprykerEventDispatcherDependencyProvider;
use ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\EventDispatcher\FirstSpiritApiControllerEventDispatcherPlugin;

class EventDispatcherDependencyProvider extends SprykerEventDispatcherDependencyProvider
{
    [...]

    /**
         * @return array<\Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface>
         */
        protected function getBackendApiEventDispatcherPlugins(): array
        {
            return [
                [...]
                new FirstSpiritApiControllerEventDispatcherPlugin(),
            ];
        }
}
```

4. Register RouterPlugin
```php
<?php

namespace Pyz\Zed\Router;

use Spryker\Zed\Router\RouterDependencyProvider as SprykerRouterDependencyProvider;
use ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiRouterPlugin;

class RouterDependencyProvider extends SprykerRouterDependencyProvider
{
    [...]

    /**
     * @return array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface>
     */
    protected function getBackendApiRouterPlugins(): array
    {
        return [
            [...]
            new FirstSpiritApiRouterPlugin(),
        ];
    }
}
```

5. Register FirstSpirit ResourcePlugins:
```php
<?php

namespace Pyz\Zed\FirstSpiritApi;

use ValanticSpryker\Zed\CategoryFirstSpiritApi\Communication\Plugin\CategoriesFirstSpiritApiResourcePlugin;
use ValanticSpryker\Zed\CmsFirstSpiritApi\Communication\Plugin\CmsFirstSpiritApiResourcePlugin;
use ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiDependencyProvider as ValanticFirstSpiritApiDependencyProvider;
use ValanticSpryker\Zed\ProductFirstSpiritApi\Communication\Plugin\ProductsFirstSpiritApiResourcePlugin;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Communication\Plugin\LookUpFirstSpiritApiResourcePlugin;
use ValanticSpryker\Zed\UrlResolverFirstSpiritApi\Communication\Plugin\StoreFrontFirstSpiritApiResourcePlugin;

class FirstSpiritApiDependencyProvider extends ValanticFirstSpiritApiDependencyProvider
{
    /**
     * @return array<\ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiResourcePluginInterface>
     */
    protected function getApiResourcePluginCollection(): array
    {
        return [
            new CategoriesFirstSpiritApiResourcePlugin(),
            new CmsFirstSpiritApiResourcePlugin(),
            new ProductsFirstSpiritApiResourcePlugin(),
            new LookUpFirstSpiritApiResourcePlugin(),
            new StoreFrontFirstSpiritApiResourcePlugin(),
        ];
    }
}
```

6. Add FS PagingSize
```php
<?php

namespace Pyz\Client\Catalog;

use Spryker\Client\Catalog\CatalogConfig as SprykerCatalogConfig;

class CatalogConfig extends SprykerCatalogConfig
{
    [...]

    /**
     * search catalog uses these values to validate product per page
     * FirstSpiritApiConstants::PAGING_SIZE should be added here as well
     *
     * @var array<int>
     */
    protected const PAGINATION_CATALOG_SEARCH_VALID_ITEMS_PER_PAGE = [10, 12, 24, 36];
}

```

## Documentation:
FS API Docs: https://docs.e-spirit.com/ecom/fsconnect-com-api/FirstSpirit_Connect_for_Commerce_Bridge_API_EN.html

# HowTos

PHP Container: `docker run -it --rm --name my-running-script -v "$PWD":/data spryker/php:latest bash`

Run Tests: `codecept run --env standalone`

Fixer: `vendor/bin/phpcbf --standard=phpcs.xml --report=full src/ValanticSpryker/`

Disable opcache: `mv /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini /usr/local/etc/php/conf.d/docker-php-ext-opcache.iniold`

XDEBUG:
- `ip addr | grep '192.'`
- `$docker-php-ext-enable xdebug`
- configure phpstorm (add 127.0.0.1 phpstorm server with name valantic)
- `$PHP_IDE_CONFIG=serverName=valantic php -dxdebug.mode=debug -dxdebug.client_host=192.168.87.39 -dxdebug.start_with_request=yes ./vendor/bin/codecept run --env standalone`

- Run Tests with coverage: `XDEBUG_MODE=coverage vendor/bin/codecept run --env standalone --coverage --coverage-xml --coverage-html`
