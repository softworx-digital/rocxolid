<?php

namespace Softworx\RocXolid\Models\Traits\Attributes;

use Illuminate\Support\Collection;

/**
 * Trait to extend the model defining additional data attributes.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait HasAdditionalDataAttributes
{
    /**
     * Retrieve additional data attributes.
     * The ADDITIONAL_DATA_ATTRIBUTES array should be defined in the model using this trait.
     *
     * @param bool $keys Flag to retrieve only attribute keys.
     * @return \Illuminate\Support\Collection
     */
    public function getAdditionalDataAttributes(bool $keys = false): Collection
    {
        return $keys
            ? collect(static::ADDITIONAL_DATA_ATTRIBUTES)
            : collect($this->getAttributes())->only(static::ADDITIONAL_DATA_ATTRIBUTES)->sortBy(function ($value, string $field) {
                return array_search($field, static::ADDITIONAL_DATA_ATTRIBUTES);
            });
    }
}
