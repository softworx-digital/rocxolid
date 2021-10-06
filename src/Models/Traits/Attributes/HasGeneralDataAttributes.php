<?php

namespace Softworx\RocXolid\Models\Traits\Attributes;

use Illuminate\Support\Collection;

/**
 * Trait to extend the model defining general data attributes.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait HasGeneralDataAttributes
{
    /**
     * Retrieve general data attributes.
     * The GENERAL_DATA_ATTRIBUTES array should be defined in the model using this trait.
     *
     * @param bool $keys Flag to retrieve only attribute keys.
     * @return \Illuminate\Support\Collection
     */
    public function getGeneralDataAttributes(bool $keys = false): Collection
    {
        return $keys
            ? collect(static::GENERAL_DATA_ATTRIBUTES)
            : collect($this->getAttributes())->only(static::GENERAL_DATA_ATTRIBUTES)->sortBy(function ($value, string $field) {
                return array_search($field, static::GENERAL_DATA_ATTRIBUTES);
            });
    }
}
