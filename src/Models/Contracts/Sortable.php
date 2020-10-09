<?php

namespace Softworx\RocXolid\Models\Contracts;

/**
 * Enables the model to have its position attribute set.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Sortable
{
    /**
     * Set model's position.
     *
     * @param integer $position
     * @return \Softworx\RocXolid\Models\Contracts\Sortable
     */
    public function setPosition(int $position): Sortable;

    /**
     * Get model's position.
     *
     * @return integer
     */
    public function getPosition(): int;
}
