<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Communication\EventListener;

use Symfony\Component\HttpKernel\Event\ControllerEvent;

interface FirstSpiritApiControllerEventListenerInterface
{
    /**
     * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $controllerEvent
     *
     * @return void
     */
    public function onKernelControllerEvent(ControllerEvent $controllerEvent): void;
}
