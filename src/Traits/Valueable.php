<?php

namespace Softworx\RocXolid\Traits;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Contracts\Valueable as ValueableContract;

/**
 * Enables object to have dynamic run-time values assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Valueable
{
    /**
     * @var mixed $default_value Default value.
     */
    private $default_value;

    /**
     * @var \Illuminate\Support\Collection $values Values container.
     */
    private $values;

    /**
     * {@inheritdoc}
     */
    public function setDefaultValue($value): ValueableContract
    {
        $this->default_value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultValue()
    {
        if (!$this->hasDefaultValue()) {
            throw new \UnderflowException(sprintf('No default value set in [%s]', get_class($this)));
        }

        return $this->default_value;
    }

    /**
     * {@inheritdoc}
     */
    public function hasDefaultValue(): bool
    {
        return isset($this->default_value);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value, int $index = 0): ValueableContract
    {
        if ($this->isArray() && ($value instanceof Collection)) {
            $value->each(function ($v, $i) {
                $this->setValue($v, $i);
            });
        } else {
            $this->getValues()->put($index, $this->adjustValueBeforeSet($value));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($default = null)
    {
        return $this->getIndexValue(0, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function getIndexValue(int $index, $default = null)
    {
        if ($this->getValues()->has($index)) {
            return $this->adjustValueBeforeGet($this->getValues()->get($index));
        } elseif (!is_null($default)) {
            return $default;
        } elseif ($this->hasDefaultValue()) {
            return $this->getDefaultValue();
        } elseif ($this->getValues()->count() && $this->isValueExpected()) {
            throw new \UnderflowException(sprintf('Invalid value index [%s] requested, available: %s', $index, implode(', ', $this->getValues()->keys()->all())));
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setValues($values): ValueableContract
    {
        $this->values = collect($values);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValues(): Collection
    {
        if (!isset($this->values)) {
            $this->values = collect();
        }

        return $this->values;
    }

    /**
     * {@inheritdoc}
     */
    public function isValidValue($value): bool
    {
        return !is_null($value);
    }

    /**
     * Adjust value before setting.
     *
     * @param mixed $value Value to adjust.
     * @return mixed
     */
    protected function adjustValueBeforeSet($value)
    {
        return $value;
    }

    /**
     * Adjust value before getting.
     *
     * @param mixed $value Value to adjust.
     * @return mixed
     */
    protected function adjustValueBeforeGet($value)
    {
        return $value;
    }

    /**
     * Check if value is expected to be in collection.
     *
     * @return bool
     */
    protected function isValueExpected(): bool
    {
        return true;
    }
}
