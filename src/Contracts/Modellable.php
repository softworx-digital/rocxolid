<?php

namespace Softworx\RocXolid\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Enables object to have a model assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Modellable
{
    /**
     * Set the model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model Model to be assigned.
     * @return \SoftSoftworx\RocXolid\Contracts\Modellable
     */
    public function setModel(Model $model): Modellable;

    /**
     * Get the assigned model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \UnderflowException If no model is set.
     */
    public function getModel(): Model;

    /**
     * Check if the model is assigned.
     *
     * @return bool
     */
    public function hasModel(): bool;
}
