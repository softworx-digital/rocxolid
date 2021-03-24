<?php

namespace Softworx\RocXolid\Models\Traits\Attributes;

use Illuminate\Support\Collection;

/**
 * Trait to extend the model defining description data attributes.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait HasDescriptionDataAttributes
{
    /**
     * Retrieve description data attributes.
     * The DESCRIPTION_DATA_ATTRIBUTES array should be defined in the model using this trait.
     *
     * @param bool $keys Flag to retrieve only attribute keys.
     * @return Collection
     */
    public function getDescriptionDataAttributes(bool $keys = false): Collection
    {
        return $keys
            ? collect(static::DESCRIPTION_DATA_ATTRIBUTES)
            : collect($this->getAttributes())->only(static::DESCRIPTION_DATA_ATTRIBUTES)->sortBy(function ($value, string $field) {
                return array_search($field, static::DESCRIPTION_DATA_ATTRIBUTES);
            });
    }
}
