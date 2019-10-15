<?php

namespace Softworx\RocXolid\Traits;

use Illuminate\Routing\Route;
use Illuminate\Http\Request;
use Softworx\RocXolid\Contracts\Routable as RoutableContract;

/**
 * Enables object to have a route assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Routable
{
    /**
     * @var \Illuminate\Routing\Route Assigned route object.
     */
    protected $route;

    /**
     * @var string $route_name Route name.
     */
    protected $route_name;

    /**
     * @var string $route_method Route method.
     */
    protected $route_method;

    /**
     * @var string $target Route target (to be used in HTML anchor element).
     */
    protected $target;

    /**
     * {@inheritdoc}
     */
    public function setRoute(Route $route): RoutableContract
    {
        $this->route = $route;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRouteName(stirng $name): RoutableContract
    {
        $this->route_name = $name;
        $this->route = route($name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRouteMethod(stirng $method): RoutableContract
    {
        $this->route_method = $method;
        $this->route = action(sprintf('\%s@%s', get_class($this->getController()), $method));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setTarget(string $target): RoutableContract
    {
        $this->target = $target;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoute(): Route
    {
        if (!$this->hasRoute()) {
            throw new \UnderflowException(sprintf('No route set in [%s]', get_class($this)));
        }

        return $this->route;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutePath(): string
    {
        if (!$this->hasRouteName()) {
            throw new \UnderflowException(sprintf('No route name set in [%s]', get_class($this)));
        }

        return route($this->route_name, [], false);
    }

    /**
     * {@inheritdoc}
     */
    public function getTarget(): string
    {
        if (!$this->hasTarget()) {
            throw new \UnderflowException(sprintf('No route target set in [%s]', get_class($this)));
        }

        return $this->target;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRoute(): bool
    {
        return isset($this->route);
    }

    /**
     * {@inheritdoc}
     */
    public function hasRouteName(): bool
    {
        return isset($this->route_name);
    }

    /**
     * {@inheritdoc}
     */
    public function hasRouteMethod(): bool
    {
        return isset($this->route_method);
    }

    /**
     * {@inheritdoc}
     */
    public function hasTarget(): bool
    {
        return isset($this->target);
    }

    /**
     * {@inheritdoc}
     */
    public function isRouteActive(): bool
    {
        return request()->fullUrlIs(sprintf('%s', $this->getRoute())) || request()->fullUrlIs(sprintf('%s/*', $this->getRoute()));
    }
}
