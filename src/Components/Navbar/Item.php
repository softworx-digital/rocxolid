<?php

namespace Softworx\RocXolid\Components\Navbar;

use Illuminate\Routing\Route;
//
use Softworx\RocXolid\Contracts\Routable;
use Softworx\RocXolid\Components\Contracts\NavbarAccessible;
use Softworx\RocXolid\Components\AbstractComponent;

class Item extends AbstractComponent implements NavbarAccessible
{
    protected $title;

    protected $icon;

    protected $open_on_routes;

    protected $items = [];

    protected $parent = null;

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function setItems($items)
    {
        $this->items = $items;

        foreach ($this->items as $item) {
            $item->parent = $this;
        }

        return $this;
    }

    public function hasItems()
    {
        return !empty($this->items);
    }

    // @todo ugly
    public function hasSubItems()
    {
        foreach ($this->items as $item) {
            if ($item->hasItems()) {
                return true;
            }
        }

        return false;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getTitle(): string
    {
        $user = auth('rocXolid')->user();

        return is_callable($this->title) ? $this->title->call($this, $user) : $this->title;
    }

    public function getTitlePath()
    {
        if (!is_null($this->parent)) {
            return sprintf('%s.%s', $this->parent->getTitlePath(), $this->getTitle());
        } else {
            return $this->getTitle();
        }
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setOpenOnRoutes(array $routes): NavbarAccessible
    {
        $this->open_on_routes = collect($routes);

        return $this;
    }

    public function isRouteActive()
    {
        if ($this->isOpenOnRoute(request()->route())) {
            return true;
        }

        foreach ($this->getItems() as $item) {
            if (($item instanceof Routable) && $item->isRouteActive()) {
                return true;
            }
        }

        return false;
    }

    protected function isOpenOnRoute(Route $route): bool
    {
        return $this->open_on_routes && $this->open_on_routes->contains($route->getName());
    }
}
