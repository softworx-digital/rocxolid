<?php

namespace Softworx\RocXolid\Models\Traits\Attributes;

use Illuminate\Support\Collection;

/**
 * Trait to extend the model defining localization data attributes.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait HasLocalizationDataAttributes
{
    /**
     * Retrieve localization data attributes.
     * The LOCALIZATION_DATA_ATTRIBUTES array should be defined in the model using this trait.
     *
     * @param bool $keys Flag to retrieve only attribute keys.
     * @return Collection
     */
    public function getLocalizationDataAttributes(bool $keys = false): Collection
    {
        return $keys
            ? collect(static::LOCALIZATION_DATA_ATTRIBUTES)
            : collect($this->getAttributes())->only(static::LOCALIZATION_DATA_ATTRIBUTES)->sortBy(function ($value, string $field) {
                return array_search($field, static::LOCALIZATION_DATA_ATTRIBUTES);
            });
    }
}
