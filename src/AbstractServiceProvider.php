<?php

namespace Softworx\RocXolid;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Softworx\RocXolid\CrudRouter;

// @todo: documentation
// @todo: use for another rocXolid packages
class AbstractServiceProvider extends IlluminateServiceProvider
{
    /**
     * Expected format:
     *      'rocXolid.<package-name>.<file-name>' => '<path>'
     * 
     * @var array $config_files Configuration files to be published and loaded.
     */
    protected $config_files = [];

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this
            ->registerPackages()
            ->bindContracts()
            ->bindAliases(AliasLoader::getInstance());
    }

     /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this
            ->configure()
            ->load()
            ->publish()
            ->setRoutes($this->app->router)
            ->setComposers()
            ->setCommads();
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

    /**
     * Load routes, migrations, views and translations.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function load()
    {
        // customized views preference
        // $this->loadViewsFrom(resource_path('views/vendor/softworx/<package-name>'), '<package-name>');
        // ...
        // pre-defined views fallback
        // $this->loadViewsFrom(__DIR__ . '/../resources/views', '<package-name>');
        // ...

        return $this;
    }

    /**
     * Expose config files and resources to be published.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function publish()
    {
        // config files
        // php artisan vendor:publish --provider="Softworx\RocXolid\<package-name\ServiceProvider" --tag="<tag>" (--force to overwrite)
        /*
        $this->publishes([
            ...
        ], '<tag>');
        */

        return $this;
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return \Illuminate\Support\ServiceProvider
     */
    private function setRoutes(Router $router)
    {
        return $this;
    }

    /**
     * Set view composers for blade templates.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function setComposers(): IlluminateServiceProvider
    {
        // View::composer('<package-name>::<template-name>', <Composer>::class);

        return $this;
    }

    /**
     * Register required third-party packages, so they don't have to be added to config/app.php.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function registerPackages()
    {
        // $this->app->register(\<ThirdPartyPackageNamespace>\<ThirdPartyPackage>ServiceProvider::class);

        return $this;
    }

    private function setCommads()
    {
        /*
        foreach (config('rocXolid.<package-name>.commands') as $command => $handler)
        {
            $this->registerCommand(sprintf(config('rocXolid.<package-name>.command-binding-pattern'), $command), $handler);
        }
        */

        return $this;
    }

    private function registerCommand($binding, $handler)
    {
        /*
        $this->app->singleton($binding, function($app) use ($handler)
        {
            return $app[$handler];
        });

        $this->app->tag($binding, config('rocXolid.<package-name>.command-binding-tag'));

        $this->commands($binding);
        */

        return $this;
    }

    /**
     * Bind contracts / facades, so they don't have to be added to config/app.php.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function bindContracts()
    {
        // rocXolid
        // $this->app->bind(<SomeContract>::class, <SomeImplementation>::class);
        // ...
        // third-party
        // ...

        return $this;
    }

    /**
     * Bind aliases, so they don't have to be added to config/app.php.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function bindAliases(AliasLoader $loader)
    {
        // rocXolid
        // $loader->alias('<alias>', <Facade/>Contract>::class);
        // ...
        // third-party
        // ...

        return $this;
    }
}
