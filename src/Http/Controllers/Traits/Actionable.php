<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

trait Actionable
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
