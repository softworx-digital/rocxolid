<?php

namespace Softworx\Rocxolid\Providers;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

/**
 * rocXolid configuration service provider.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class ConfigurationServiceProvider extends IlluminateServiceProvider
{
    /**
     * @var array $config_files Configuration files to be published and loaded.
     */
    private $config_files = [
        'rocXolid.main' => '/../../config/main.php'
    ];

    /**
     * Extend the default request validator.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function boot()
    {
        $this
            ->configure();

        return $this;
    }

    /**
     * Set configuration files for loading.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function configure(): IlluminateServiceProvider
    {
        foreach ($this->config_files as $key => $path) {
            $path = realpath(__DIR__ . $path);

            if (file_exists($path)) {
                $this->mergeConfigFrom($path, $key);
            }
        }

        return $this;
    }
}
