## Implementation:
- add `ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\EventDispatcher\FirstSpiritApiControllerEventDispatcherPlugin` to `Pyz\Zed\EventDispatcher\EventDispatcherDependencyProvider::getBackendApiEventDispatcherPlugins()`
- add `ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin\FirstSpiritApiRouterPlugin` to `Pyz\Zed\Router\RouterDependencyProvider::getBackendApiRouterPlugins()`
- add config values to `config_default.php`
  - `$config[FirstSpiritApiConstants::IS_FIRST_SPIRIT_API_DEBUG_ENABLED] = false;`
  - `$config[FirstSpiritApiConstants::PAGING_SIZE] = 10;`
  - `$config[FirstSpiritApiConstants::FIRST_SPIRIT_SPA_URL] = ($backofficePort === 80 ? 'http://' : 'https://') . $firstSpiritSpaHost;`
- implement `Pyz\Zed\FirstSpiritApi\FirstSpiritApiDependencyProvider` and extend from `ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiDependencyProvider` and add plugins to `getApiResourcePluginCollection()`
```
new CategoriesFirstSpiritApiResourcePlugin(),
new CmsFirstSpiritApiResourcePlugin(),
new ProductsFirstSpiritApiResourcePlugin(),
new LookUpFirstSpiritApiResourcePlugin(),
new StoreFrontFirstSpiritApiResourcePlugin(),
```
  
