<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Utils;

/**
 * Utility trait to forge URL for controller's action.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait CreatesRoutes
{
    /**
     * Dynamically create route for given controller action.
     *
     * @param string $route_action
     * @return string
     */
    public function getRoute(string $route_action, ...$params): string
    {
        $action = sprintf('\%s@%s', get_class($this), $route_action);

        return action($action, ...$params);
    }
}
