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

    public function getTranslationKey($key, $use_repository_param)
    {
        if (!$use_repository_param) {
            return sprintf('navbar.%s', $key);
        } elseif (method_exists($this, 'getRepository') && $this->getRepository()) {
            return sprintf('navbar.%s', $this->getRepository()->getTranslationParam(), $key);
        } else {//if ($this->getController() && $this->getController()->getRepository())
            return '---component--- (' . __METHOD__ . ')';
        }

        return $key;
    }
}
