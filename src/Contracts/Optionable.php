<?php

namespace Softworx\RocXolid\Contracts;

use Illuminate\Support\Collection;

/**
 * Enables object to have options assigned dynamically.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Optionable
{
    /**
     * Set option with given value.
     *
     * @param string $option Option name - key.
     * @param mixed $value Option value. Can be anything.
     * @return \Softworx\RocXolid\Contracts\Optionable;
     */
    public function setOption(string $option, $value): Optionable;

    /**
     * Set/replace multiple options at once.
     *
     * @param array $option Option map to set.
     * @return \Softworx\RocXolid\Contracts\Optionable;
     */
    public function setOptions(array $options): Optionable;

    /**
     * Get the option's value.
     *
     * @param string $option Option name - key.
     * @param mixed $default Default value. If provided, will be returned if given option is not set.
     * @return mixed
     */
    public function getOption(string $option, $default = null);

    /**
     * Get all options.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getOptions(): Collection;

    /**
     * Merge existing options with provided.
     *
     * @param array $options Options to merge.
     * @return \Softworx\RocXolid\Contracts\Optionable
     */
    public function mergeOptions(array $options): Optionable;

    /**
     * Remove option.
     *
     * @param string $option Option name - key to remove.
     * @param bool $report Whether to throw an exception if option not previously set.
     * @return \Softworx\RocXolid\Contracts\Optionable
     * @throws \UnderflowException If the given option is not set.
     */
    public function removeOption(string $option, bool $report = false): Optionable;

    /**
     * Get all the option keys.
     *
     * @return array
     */
    public function getOptionsKeys(): array;

    /**
     * Check if the option is set.
     *
     * @return bool
     */
    public function hasOption(string $option): bool;
}
