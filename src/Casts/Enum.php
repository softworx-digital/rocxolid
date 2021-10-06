<?php

namespace Softworx\RocXolid\Casts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
// rocXolid model contracts
use Softworx\RocXolid\Models\Contracts\Crudable;

/**
 * Enum casting mutator.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class Enum implements CastsAttributes
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
        return $value;
    }

    /**
     * Retrieve viewable (translated) value for model's raw value.
     *
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @param string $attribute
     * @return string|null
     */
    public function format(Crudable $model, string $attribute): ?string
    {
        return filled($model->$attribute) ? $model->getModelViewerComponent()->translate(sprintf('choice.%s.%s', $attribute, $model->$attribute)) : null;
    }
}