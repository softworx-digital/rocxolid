<?php

namespace Softworx\RocXolid\Models\Traits;

trait HasTokenablePropertiesMethods
{
    public function hasTokenableProperties()
    {
        return property_exists($this, 'tokenable_properties');
    }

    public function getTokenableProperties()
    {
        return collect(static::$tokenable_properties);
    }

    public function hasTokenableMethods()
    {
        return property_exists($this, 'tokenable_methods');
    }

    public function getTokenableMethods()
    {
        return collect(static::$tokenable_methods);
    }
}
