<?php

namespace Softworx\RocXolid\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * Decimal casting mutator.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class Decimal implements CastsAttributes
{
    /**
     * @inheritDoc
     */
    public function get($model, $key, $value, $attributes)
    {
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function set($model, $key, $value, $attributes)
    {
        $value = str_replace(',', '.', $value);
        $value = str_replace(' ', '', $value);

        return $value;
    }
}