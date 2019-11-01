<?php

namespace Softworx\RocXolid\Components\Navbar;

use Softworx\RocXolid\Components\Contracts\NavbarAccessible;
use Softworx\RocXolid\Components\AbstractComponent;

class Item extends AbstractComponent implements NavbarAccessible
{
    protected $title;

    protected $icon;

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
        return count($this->items);
    }

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

    public function getTitle()
    {
        return $this->title;
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
}
