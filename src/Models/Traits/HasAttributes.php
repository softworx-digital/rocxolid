<?php

namespace Softworx\RocXolid\Models\Traits;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Trait to handle model attributes.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo quick'n'dirty
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
        } elseif ($this->isMonetaryAttribute($attribute)) {
            return $this->getMonetaryAttributeViewValue($attribute);
        } elseif ($this->isPercentualAttribute($attribute)) {
            return $this->getPercentualAttributeViewValue($attribute);
        } elseif ($this->isRelationAttribute($attribute)) {
            return $this->getRelationAttributeViewValue($attribute);
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
        return filled($this->$attribute) ? Carbon::make($this->$attribute)->locale(app()->getLocale())->isoFormat('l') : null;
    }

    /**
     * Check if attribute is of datetime type.
     *
     * @param string $attribute
     * @return bool
     */
    public function isDateTimeAttribute(string $attribute): bool
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
        return filled($this->$attribute) ? Carbon::make($this->$attribute)->locale(app()->getLocale())->isoFormat('llll') : null;
    }

    /**
     * Check if attribute is of decimal type.
     *
     * @param string $attribute
     * @return bool
     */
    public function isDecimalAttribute(string $attribute): bool
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
        $nf->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 2);
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
    public function isEnumAttribute(string $attribute): bool
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
        return filled($this->$attribute) ? $this->getModelViewerComponent()->translate(sprintf('choice.%s.%s', $attribute, $this->$attribute)) : null;
    }

    // @todo "hotfixed" to enable array fields to be filled correctly
    public function decimalize($values)
    {
        $nf = new \NumberFormatter(app()->getLocale(), \NumberFormatter::DECIMAL);
        $nf->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 2);
        $nf->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 8);
        // $nf->setAttribute(\NumberFormatter::GROUPING_SIZE, 0);

        if (is_scalar($values)) {
            return $nf->format($values);
        }

        return collect($values)->transform(function ($value) use ($nf) {
            return $nf->format($value);
        });
    }

    /**
     * Check if attribute is of monetary type.
     *
     * @param string $attribute
     * @return bool
     */
    public function isMonetaryAttribute(string $attribute): bool
    {
        return collect($this->monetaries)->contains($attribute);
    }

    /**
     * Retrieve monetary type attribute value for a view.
     *
     * @param string $attribute
     * @return mixed
     * @todo make not tied to hard-coded locale & currency, find a convenient (yet reliable) way
     */
    protected function getMonetaryAttributeViewValue(string $attribute)
    {
        $formatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($this->$attribute ?? 0, 'EUR');
    }

    /**
     * Check if attribute is of percentual type.
     *
     * @param string $attribute
     * @return bool
     */
    public function isPercentualAttribute(string $attribute): bool
    {
        return collect($this->percentuals)->contains($attribute);
    }

    /**
     * Retrieve percentual type attribute value for a view.
     *
     * @param string $attribute
     * @return mixed
     * @todo make not tied to hard-coded locale & currency, find a convenient (yet reliable) way
     */
    protected function getPercentualAttributeViewValue(string $attribute)
    {
        $formatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::PERCENT);

        return $formatter->format(filled($this->$attribute) ? $this->$attribute / 100 : 0);
    }

    /**
     * Check if attribute is of relation type.
     *
     * @param string $attribute
     * @return bool
     */
    public function isRelationAttribute(string $attribute): bool
    {
        $attribute = Str::camel(Str::beforeLast($attribute, '_id'));

        // @todo this makes troubles for methods that have nothing to do with attributes
        // eg. Role has 'guard' attribute and the code calls $role::guard() method which is used for totally unrelated functionality
        // hotfixed by try & catch
        try {
            return filled($attribute) && method_exists($this, $attribute) && ($this->{$attribute}() instanceof Relation);
        } catch (\Throwable $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * Retrieve relation type attribute value for a view.
     *
     * @param string $attribute
     * @return string|null
     */
    protected function getRelationAttributeViewValue(string $attribute): ?string
    {
        $attribute = Str::camel(Str::beforeLast($attribute, '_id'));

        return optional($this->$attribute)->getTitle();
    }
}
