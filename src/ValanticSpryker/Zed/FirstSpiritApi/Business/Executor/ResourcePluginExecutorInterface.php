<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\FirstSpiritApi\Business\Executor;

interface ResourcePluginExecutorInterface
{
    /**
     * @param string $resource
     * @param string $method
     * @param int|null $id
     * @param array $params
     *
     * @return mixed
     */
    public function execute(string $resource, string $method, ?int $id, array $params): mixed;
}
