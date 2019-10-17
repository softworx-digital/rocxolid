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
     * Set the route object.
     *
     * @param \Illuminate\Routing\Route $route Route object to set.
     * @return `\Softworx\RocXolid\Contracts\Routable
    */
    public function setRoute(Route $route): Routable;

    /**
     * Set the route by its name.
     *
     * @param string $name Route name to set.
     * @return \Softworx\RocXolid\Contracts\Routable
     */
    public function setRouteName(string $name): Routable;

    /**
     * Set the route method.
     *
     * @param string $method Route method to set.
     * @return \Softworx\RocXolid\Contracts\Routable
     */
    public function setRouteMethod(string $method): Routable;

    /**
     * Set the route target (to be used in HTML anchor element).
     *
     * @param string $target Route target to set.
     * @return \Softworx\RocXolid\Contracts\Routable
     */
    public function setTarget(string $target): Routable;

    /**
     * Get the route object.
     *
     * @return \Illuminate\Routing\Route
     * @throws \UnderflowException If no route is set.
     */
    public function getRoute(): Route;

    /**
     * Get the route path.
     *
     * @return string
     * @throws \UnderflowException If no route path is set.
     */
    public function getRoutePath(): string;

    /**
     * Get the route target.
     *
     * @return string
     * @throws \UnderflowException If no route target is set.
     */
    public function getTarget(): string;

    /**
     * Check if the route is assigned.
     *
     * @return bool
     */
    public function hasRoute(): bool;

    /**
     * Check if the route name is assigned.
     *
     * @return bool
     */
    public function hasRouteName(): bool;

    /**
     * Check if the route target is assigned.
     *
     * @return bool
     */
    public function hasTarget(): bool;

    /**
     * Check if the route is active (last client's request).
     *
     * @return bool
     */
    public function isRouteActive(): bool;
}
