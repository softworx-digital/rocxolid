<?php

namespace Softworx\RocXolid\Models\Traits;

use Illuminate\Support\Collection;

/**
 * Access to model's properties and methods to be used as tokens in content editing.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait HasTokenablePropertiesMethods
{
    /**
     * {@inheritDoc}
     */
    public function hasTokenableProperties(): bool
    {
        return property_exists($this, 'tokenable_properties');
    }

    /**
     * {@inheritDoc}
     */
    public function getTokenableProperties(): Collection
    {
        return collect(static::$tokenable_properties);
    }

    /**
     * {@inheritDoc}
     */
    public function hasTokenableMethods(): bool
    {
        return property_exists($this, 'tokenable_methods');
    }

    /**
     * {@inheritDoc}
     */
    public function getTokenableMethods(): Collection
    {
        return collect(static::$tokenable_methods);
    }
}
