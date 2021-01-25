<?php

namespace Softworx\RocXolid\Models\Traits\Attributes;

use Illuminate\Support\Collection;

/**
 * Trait to extend the model defining label data attributes.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait HasLabelDataAttributes
{
    /**
     * Retrieve label data attributes.
     * The LABEL_DATA_ATTRIBUTES array should be defined in the model using this trait.
     *
     * @param boolean $keys Flag to retrieve only attribute keys.
     * @return Collection
     */
    public function getLabelDataAttributes(bool $keys = false): Collection
    {
        return $keys
            ? collect(static::LABEL_DATA_ATTRIBUTES)
            : collect($this->getAttributes())->only(static::LABEL_DATA_ATTRIBUTES)->sortBy(function ($value, string $field) {
                return array_search($field, static::LABEL_DATA_ATTRIBUTES);
            });
    }
}
