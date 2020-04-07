<?php

namespace Softworx\RocXolid\Forms\Traits;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Forms\Contracts\Buttonable as ButtonableContract;
use Softworx\RocXolid\Forms\Contracts\FormField;

trait Buttonable
{
    private $_buttontoolbars = null;

    private $_buttongroups = null;

    private $_buttons = null;

    public function addButton(FormField $button): ButtonableContract
    {
        $this->getButtons()->put($button->getName(), $button);

        return $this;
    }

    public function setButtonToolbars($buttontoolbars): ButtonableContract
    {
        $this->_buttontoolbars = collect($buttontoolbars);

        return $this;
    }

    public function setButtonGroups($buttongroups): ButtonableContract
    {
        $this->_buttongroups = collect($buttongroups);

        return $this;
    }

    public function setButtons($buttons): ButtonableContract
    {
        $this->_buttons = collect($buttons);

        return $this;
    }

    public function getButtonToolbars(): Collection
    {
        if (is_null($this->_buttontoolbars)) {
            $this->_buttontoolbars = collect();
        }

        return $this->_buttontoolbars;
    }

    public function getButtonGroups(): Collection
    {
        if (is_null($this->_buttongroups)) {
            $this->_buttongroups = collect();
        }

        return $this->_buttongroups;
    }

    public function getButtons(): Collection
    {
        if (is_null($this->_buttons)) {
            $this->_buttons = collect();
        }

        return $this->_buttons;
    }

    public function hasButtons(): bool
    {
        // @todo: temporary fix, caused troubles for modal forms
        return true;
        // return $this->getButtons()->isEmpty();
    }

    public function getButtonToolbar($name): FormField
    {
        if ($this->getButtontoolbars()->has($name)) {
            return $this->getButtontoolbars()->get($name);
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid button toolbar (name) [%s] requested', $name));
        }
    }

    public function getButtonGroup($name): FormField
    {
        if ($this->getButtongroups()->has($name)) {
            return $this->getButtongroups()->get($name);
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid button group (name) [%s] requested', $name));
        }
    }

    public function getButton($name): FormField
    {
        if ($this->getButtons()->has($name)) {
            return $this->getButtons()->get($name);
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid button (name) [%s] requested', $name));
        }
    }
}
