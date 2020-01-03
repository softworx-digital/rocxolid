<?php

namespace Softworx\RocXolid\Providers;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
// use Vsch\TranslationManager\Translator; // @todo: not yet integrated, in use: \Barryvdh\TranslationManager\ManagerServiceProvider

/**
 * rocXolid third party packages' service provider.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class PackagesServiceProvider extends IlluminateServiceProvider
{
    /**
     * Register required third-party packages, so they don't have to be added to config/app.php.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function register(): IlluminateServiceProvider
    {
        $this->app->register(\Collective\Html\HtmlServiceProvider::class);
        $this->app->register(\Barryvdh\TranslationManager\ManagerServiceProvider::class);
        //$this->app->register(\Vsch\TranslationManager\ManagerServiceProvider::class);
        //$this->app->register(\Vsch\TranslationManager\TranslationServiceProvider::class);
        $this->app->register(\Spatie\Permission\PermissionServiceProvider::class);
        $this->app->register(\Intervention\Image\ImageServiceProvider::class);
        //$this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);

        return $this;
    }
}
