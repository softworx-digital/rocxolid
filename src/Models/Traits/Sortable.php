<?php

namespace Softworx\RocXolid\Models\Traits;

use Softworx\RocXolid\Models\Contracts\Sortable as SortableContract;

/**
 * Trait to enable the model to have its position attribute set.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Sortable
{
    /**
     * {@inheritDoc}
     */
    public function setPosition(int $position): SortableContract
    {
        $this->position = $position;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPosition(): int
    {
        return $this->position;
    }
}
