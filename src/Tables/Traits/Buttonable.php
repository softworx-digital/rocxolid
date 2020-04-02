<?php

namespace Softworx\RocXolid\Tables\Traits;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Tables\Contracts\Buttonable as ButtonableContract;
use Softworx\RocXolid\Tables\Contracts\Button;

/**
 * Enables to assign buttons.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Buttonable
{
    /**
     * Assigned buttons collection.
     *
     * @var \Illuminate\Support\Collection
     */
    private $_buttons = null;

    /**
     * {@inheritDoc}
     */
    public function addButton(Button $button): ButtonableContract
    {
        $this->getButtons()->put($button->getName(), $button);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setButtons(Collection $buttons): ButtonableContract
    {
        $this->_buttons = $buttons;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getButtons(): Collection
    {
        if (is_null($this->_buttons)) {
            $this->_buttons = collect();
        }

        return $this->_buttons;
    }

    /**
     * {@inheritDoc}
     */
    public function getButton(string $name): Button
    {
        if ($this->getButtons()->has($name)) {
            return $this->getButtons()->get($name);
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid button (name) [%s] requested', $name));
        }
    }
}
