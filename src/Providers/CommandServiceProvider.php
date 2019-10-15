<?php

namespace Softworx\Rocxolid\Providers;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Console\Command;

/**
 * RocXolid CLI commands service provider.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class CommandServiceProvider extends IlluminateServiceProvider
{
    /**
     * Extend the default request validator.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function boot()
    {
        $this
            ->setCommads();

        return $this;
    }

    /**
     * Read commands config and register found commands with assigned handlers.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function setCommads(): IlluminateServiceProvider
    {
        /*
        foreach (config('rocXolid.<package>.commands') as $command => $handler)
        {
            $this->registerCommand(sprintf(config('rocXolid.<package>.command-binding-pattern'), $command), $handler);
        }
        */

        return $this;
    }

    /**
     * Register CLI command.
     *
     * @param string $binding
     * @param \Illuminate\Console\Command $handler
     * @return \Illuminate\Support\ServiceProvider
     */
    private function registerCommand(string $binding, \Illuminate\Console\Command $handler): IlluminateServiceProvider
    {
        /*
        $this->app->singleton($binding, function($app) use ($handler)
        {
            return $app[$handler];
        });

        $this->app->tag($binding, config('rocXolid.<package>.command-binding-tag'));

        $this->commands($binding);
        */

        return $this;
    }
}
