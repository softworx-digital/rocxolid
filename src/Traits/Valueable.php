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
     * @var string $default_value Default value.
     */
    private $default_value;

    /**
     * @var \Illuminate\Support\Collection $values Values container.
     */
    private $values;

    /**
     * {@inheritdoc}
     */
    public function setDefaultValue(string $value): ValueableContract
    {
        $this->default_value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultValue(): string
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
    public function setValue($value, $index = 0): ValueableContract
    {
        $this->getValues()->put($index, $this->adjustValueBeforeSet($value));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(string $default = null)
    {
        return $this->getIndexValue(0, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function getIndexValue($index, $default = null)
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
        $this->values = new Collection($values);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValues(): Collection
    {
        if (!isset($this->values)) {
            $this->values = new Collection();
        }

        return $this->values;
    }

    /**
     * {@inheritdoc}
     */
    public function isValidValue(string $value): bool
    {
        return !is_null($value);
    }

    /**
     * Adjust value before setting.
     *
     * @param string $value Value to adjust.
     * @return string
     */
    protected function adjustValueBeforeSet(string $value): string
    {
        return $value;
    }

    /**
     * Adjust value before getting.
     *
     * @param string $value Value to adjust.
     * @return string
     */
    protected function adjustValueBeforeGet(string $value): string
    {
        return $value;
    }
}
