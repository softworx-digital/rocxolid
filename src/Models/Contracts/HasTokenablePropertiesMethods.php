<?php

namespace Softworx\RocXolid\Models\Contracts;

use Illuminate\Support\Collection;

/**
 * Access to model's properties and methods to be used as tokens in content editing.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface HasTokenablePropertiesMethods
{
    /**
     * Check model has some tokenable properties.
     *
     * @return bool
     */
    public function hasTokenableProperties(): bool;

    /**
     * Get model's tokenable properties.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTokenableProperties(): Collection;

    /**
     * Check model has some tokenable methods.
     *
     * @return bool
     */
    public function hasTokenableMethods(): bool;

    /**
     * Get model's tokenable methods.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTokenableMethods(): Collection;
}
