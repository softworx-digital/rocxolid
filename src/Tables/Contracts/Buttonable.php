<?php

namespace Softworx\RocXolid\Tables\Contracts;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Tables\Contracts\Button;

/**
 * Enables to assign buttons.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Buttonable
{
    /**
     * Add button to container.
     *
     * @param \Softworx\RocXolid\Tables\Contracts\Button $button
     * @return \Softworx\RocXolid\Tables\Contracts\Buttonable
     */
    public function addButton(Button $button): Buttonable;

    /**
     * Replace the buttons with new buttons collection.
     *
     * @param \Illuminate\Support\Collection $buttons
     * @return \Softworx\RocXolid\Tables\Contracts\Buttonable
     */
    public function setButtons(Collection $buttons): Buttonable;

    /**
     * Get assigned buttons.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getButtons(): Collection;

    /**
     * Get single button by its name.
     *
     * @param string $name
     * @return \Softworx\RocXolid\Tables\Contracts\Button
     */
    public function getButton(string $name): Button;
}
