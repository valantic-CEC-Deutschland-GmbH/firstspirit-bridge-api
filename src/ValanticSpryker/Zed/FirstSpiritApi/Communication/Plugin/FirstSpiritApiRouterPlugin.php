<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @method \ValanticSpryker\Zed\FirstSpiritApi\Business\FirstSpiritApiFacadeInterface getFacade()
 * @method \ValanticSpryker\Zed\FirstSpiritApi\FirstSpiritApiConfig getConfig()
 * @method \ValanticSpryker\Zed\FirstSpiritApi\Communication\FirstSpiritApiCommunicationFactory getFactory()
 */
class FirstSpiritApiRouterPlugin extends AbstractPlugin implements RouterPluginInterface
{
    /**
     * @return \Symfony\Component\Routing\RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->getFactory()
            ->createApiRouter();
    }
}
