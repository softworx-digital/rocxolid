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
     * @var string Assigned route.
     */
    protected $route;

    /**
     * @var string $route_name Route name.
     */
    protected $route_name;

    /**
     * @var string $route_action Route action.
     */
    protected $route_action;

    /**
     * @var string $target Route target (to be used in HTML anchor element).
     */
    protected $target;

    /**
     * {@inheritdoc}
     */
    public function setRoute(string $route): RoutableContract
    {
        $this->route = $route;

        return $this;
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
    public function getRoute(): string
    {
        if (!$this->hasRoute()) {
            throw new \UnderflowException(sprintf('No route set in [%s]', get_class($this)));
        }

        return $this->route;
    }

    /**
     * {@inheritdoc}
     */
    public function setRouteName(string $name): RoutableContract
    {
        $this->route_name = $name;
        $this->route = route($name);

        return $this;
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
    public function getRouteName(): string
    {
        if (!$this->hasRouteName()) {
            throw new \UnderflowException(sprintf('No route name set in [%s]', get_class($this)));
        }

        return $this->route_name;
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
    public function hasTarget(): bool
    {
        return isset($this->target);
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
     * @todo getController() not guaranteed
     */
    public function setRouteAction(string $action): RoutableContract
    {
        $this->route_action = $action;
        $this->route = action(sprintf('\%s@%s', get_class($this->getController()), $action));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRouteAction(): bool
    {
        return isset($this->route_action);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteAction(): string
    {
        if (!$this->hasRouteAction()) {
            throw new \UnderflowException(sprintf('No route action set in [%s]', get_class($this)));
        }

        return $this->route_action;
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
    public function isRouteActive(): bool
    {
        return request()->fullUrlIs(sprintf('%s', $this->getRoute())) || request()->fullUrlIs(sprintf('%s/*', $this->getRoute()));
    }

    /**
     * {@inheritdoc}
     */
    public function isRouteNameActive(): bool
    {
        return request()->routeIs($this->getRouteName());
    }
}
