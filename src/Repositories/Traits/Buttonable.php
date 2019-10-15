<?php

namespace Softworx\RocXolid\Repositories\Traits;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Repositories\Contracts\Buttonable as ButtonableContract;
use Softworx\RocXolid\Repositories\Contracts\Column;

trait Buttonable
{
    private $_buttons = null;

    public function addButton(Column $button): ButtonableContract
    {
        $this->getButtons()->put($button->getName(), $button);

        return $this;
    }

    public function setButtons($buttons): ButtonableContract
    {
        $this->_buttons = new Collection($buttons);

        return $this;
    }

    public function getButtons(): Collection
    {
        if (is_null($this->_buttons)) {
            $this->_buttons = new Collection();
        }

        return $this->_buttons;
    }

    public function getButton($name): Column
    {
        if ($this->getButtons()->has($name)) {
            return $this->getButtons()->get($name);
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid button (name) [%s] requested', $name));
        }
    }
}
