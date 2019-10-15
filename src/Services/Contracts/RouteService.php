<?php

namespace Softworx\RocXolid\Services\Contracts;

interface RouteService
{
    /**
     * Returns route for current controller and given action.
     *
     * @param string $action Route action.
     * @param array $params Route parameters.
     * @return string
     */
    public function getRoute(string $action, array $params = null);
}
