<?php

namespace Softworx\RocXolid\Models\Traits\Attributes;

use Illuminate\Support\Facades\Hash;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Trait to extend the model defining password attribute.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait HasPasswordAttribute
{
    /**
     * Hash password before storing.
     *
     * @param string $password Unhashed password.
     * @return \Softworx\RocXolid\Models\Contracts\Crudable
     */
    public function setPasswordAttribute(string $password): Crudable
    {
        $this->attributes['password'] = Hash::make($password);

        return $this;
    }
}
