<?php

namespace Softworx\RocXolid\Contracts;

use Illuminate\Support\Collection;

/**
 * Enables object to have dynamic run-time values assigned.
 *
 * @todo Split for single / multi values.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Valueable
{
    /**
     * Set default value to be returned if value is not available.
     *
     * @param string $value Default value to be set.
     * @return \Softworx\RocXolid\Contracts\Valueable
     */
    public function setDefaultValue(string $value): Valueable;

    /**
     * Get the default value.
     *
     * @return string
     * @throws \UnderflowException If no default value is set.
     */
    public function getDefaultValue(): string;

    /**
     * Check if default value is set.
     *
     * @return bool
     */
    public function hasDefaultValue(): bool;

    /**
     * Set value at given index.
     *
     * @param string $value Value to set.
     * @param int $index Collection position to set the value at.
     * @return \Softworx\RocXolid\Contracts\Valueable
     */
    public function setValue(string $value, int $index = 0): Valueable;

    /**
     * Get the value at first position.
     *
     * @param string $default Default value to return if value is not set.
     * @return mixed
     * @throws \UnderflowException If value not set and no default value is available.
     */
    public function getValue(string $default = null);

    /**
     * Get the value at given position.
     *
     * @param int $index Collection position to get the value at.
     * @param string $default Default value to return if value is not set.
     * @return mixed
     * @throws \UnderflowException If value not set and no default value is available.
     */
    public function getIndexValue(int $index, string $default = null);

    /**
     * Set the values at once.
     *
     * @param array Values to set.
     * @return \Softworx\RocXolid\Contracts\Valueable
     */
    public function setValues(array $values): Valueable;

    /**
     * Get the values at once.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getValues(): Collection;

    /**
     * Check if provided value is valid for this type.
     *
     * @param string $value Value to check.
     * @return bool
     */
    public function isValidValue(string $value): bool;
}
