<?php

namespace Softworx\RocXolid\Models\Traits\Utils;

/**
 * Trait to enable the model to have its configuration.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Configurable
{
    /**
     * Obtain config file path key.
     *
     * @return string
     */
    abstract protected static function getConfigFilePathKey(): string;

    /**
     * Obtain model specific (fallbacks to 'default') configuration.
     *
     * @param string $key
     * @return \Illuminate\Support\Collection
     */
    protected static function getConfigData(string $key): Collection
    {
        $config = static::getConfigFilePathKey();

        return collect(config(sprintf('%s.%s.%s', $config, $key, static::class), config(sprintf('%s.%s.default', $config, $key), [])));
    }
}
