<?php

namespace Softworx\RocXolid\Models\Traits;

use Carbon\Carbon;

/**
 * Trait to handle model attributes.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait HasAttributes
{
    /**
     * Get attribute value to be used in views.
     *
     * @param string $attribute
     * @return mixed
     */
    public function getAttributeViewValue(string $attribute)
    {
        if ($this->isDateAttribute($attribute)) {
            return $this->getDateAttributeViewValue($attribute);
        } elseif ($this->isDateTimeAttribute($attribute)) {
            return $this->getDateTimeAttributeViewValue($attribute);
        } elseif ($this->isDecimalAttribute($attribute)) {
            return $this->getDecimalAttributeViewValue($attribute);
        } elseif ($this->isEnumAttribute($attribute)) {
            return $this->getEnumAttributeViewValue($attribute);
        }

        return $this->$attribute;
    }

    /**
     * Get attribute value to be used in form fields.
     *
     * @param string $attribute
     * @return mixed
     */
    public function getAttributeFieldValue(string $attribute)
    {
        if ($this->isDecimalAttribute($attribute)) {
            return $this->getDecimalAttributeViewValue($attribute, false);
        }

        return $this->$attribute;
    }

    /*
    // this is in Illuminate\Database\Eloquent\Concerns\HasAttributes
    protected function isDateAttribute(string $attribute)
    {
        return collect($this->dates)->contains($attribute);
    }
    */

    /**
     * Retrieve date type attribute value for a view.
     *
     * @param string $attribute
     * @return mixed
     */
    protected function getDateAttributeViewValue(string $attribute)
    {
        return $this->$attribute ? Carbon::make($this->$attribute)->locale(app()->getLocale())->isoFormat('l') : null;
    }

    /**
     * Check if attribute is of datetime type.
     *
     * @param string $attribute
     * @return bool
     */
    protected function isDateTimeAttribute(string $attribute): bool
    {
        return collect($this->date_times)->contains($attribute);
    }

    /**
     * Retrieve datetime type attribute value for a view.
     *
     * @param string $attribute
     * @return mixed
     */
    protected function getDateTimeAttributeViewValue(string $attribute)
    {
        return $this->$attribute ? Carbon::make($this->$attribute)->locale(app()->getLocale())->isoFormat('llll') : null;
    }

    /**
     * Check if attribute is of decimal type.
     *
     * @param string $attribute
     * @return bool
     */
    protected function isDecimalAttribute(string $attribute): bool
    {
        return collect($this->decimals)->contains($attribute);
    }

    /**
     * Retrieve decimal type attribute value for a view.
     *
     * @param string $attribute
     * @param bool $grouping
     * @return mixed
     */
    protected function getDecimalAttributeViewValue(string $attribute, bool $grouping = true)
    {
        if (is_null($this->$attribute)) {
            return null;
        }

        $nf = new \NumberFormatter(app()->getLocale(), \NumberFormatter::DECIMAL);
        $nf->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 8);

        if (!$grouping) {
            $nf->setAttribute(\NumberFormatter::GROUPING_USED, 0);
        }

        return $nf->format($this->$attribute);
    }

    /**
     * Check if attribute is of enum type.
     *
     * @param string $attribute
     * @return bool
     */
    protected function isEnumAttribute(string $attribute): bool
    {
        return collect($this->enums)->contains($attribute);
    }

    /**
     * Retrieve enum type attribute value for a view.
     *
     * @param string $attribute
     * @return mixed
     */
    protected function getEnumAttributeViewValue(string $attribute)
    {
        return !is_null($this->$attribute) ? $this->getModelViewerComponent()->translate(sprintf('choice.%s.%s', $attribute, $this->$attribute)) : null;
    }
}
