<?php

namespace Softworx\RocXolid\Contracts;

use Illuminate\Routing\Route;

/**
 * Enables object to have a route assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Routable
{
    /**
     * Set the route.
     *
     * @param string $route Route to set.
     * @return `\Softworx\RocXolid\Contracts\Routable
    */
    public function setRoute(string $route): Routable;

    /**
     * Check if the route is assigned.
     *
     * @return bool
     */
    public function hasRoute(): bool;

    /**
     * Get the route.
     *
     * @return string
     * @throws \UnderflowException If no route is set.
     */
    public function getRoute(): string;

    /**
     * Set the route by its name.
     *
     * @param string $name Route name to set.
     * @return \Softworx\RocXolid\Contracts\Routable
     */
    public function setRouteName(string $name): Routable;

    /**
     * Check if the route name is assigned.
     *
     * @return bool
     */
    public function hasRouteName(): bool;

    /**
     * Get the route name.
     *
     * @return string
     * @throws \UnderflowException If no route name is set.
     */
    public function getRouteName(): string;

    /**
     * Set the route target (to be used in HTML anchor element).
     *
     * @param string $target Route target to set.
     * @return \Softworx\RocXolid\Contracts\Routable
     */
    public function setTarget(string $target): Routable;

    /**
     * Check if the route target is assigned.
     *
     * @return bool
     */
    public function hasTarget(): bool;

    /**
     * Get the route target.
     *
     * @return string
     * @throws \UnderflowException If no route target is set.
     */
    public function getTarget(): string;

    /**
     * Set the route controller action.
     *
     * @param string $action Route action to set.
     * @return \Softworx\RocXolid\Contracts\Routable
     */
    public function setRouteAction(string $action): Routable;

    /**
     * Check if the route controller action is assigned.
     *
     * @return bool
     */
    public function hasRouteAction(): bool;

    /**
     * Get the route controller action.
     *
     * @return string
     * @throws \UnderflowException If no route controller action is set.
     */
    public function getRouteAction(): string;

    /**
     * Get the route path.
     *
     * @return string
     * @throws \UnderflowException If no route path is set.
     */
    public function getRoutePath(): string;

    /**
     * Check if the route is active (last client's request).
     *
     * @return bool
     */
    public function isRouteActive(): bool;

    /**
     * Check if the route is active (last client's request) using route name for comparison.
     *
     * @return bool
     */
    public function isRouteNameActive(): bool;
}
