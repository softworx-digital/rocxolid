<?php

namespace Softworx\RocXolid;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
// use Vsch\TranslationManager\Translator; // @TODO: not yet integrated, in use: \Barryvdh\TranslationManager\ManagerServiceProvider

/**
 * RocXolid package service provider.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * @var array $listen Event listeners setup.
     */
    protected $listen = [
        Illuminate\Auth\Events\Login::class => [
            App\Listeners\LogSuccessfulLogin::class,
        ],
    ];

    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(Providers\ConfigurationServiceProvider::class);
        $this->app->register(Providers\ValidationServiceProvider::class);
        $this->app->register(Providers\PackagesServiceProvider::class);
        $this->app->register(Providers\CommandServiceProvider::class);

        $this
            ->bindContracts()
            ->bindAliases(AliasLoader::getInstance());
    }

    /**
     * Bootstrap application services.
     *
     * @return void
     */
    public function boot()
    {
        $this
            ->load()
            ->publish();

        //\Debugbar::disable(); // @todo - zrejme pozdla usera enablovat / disablovat - najst kam spravne pichnut
    }

    /**
     * Load routes, migrations and translations.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function load(): IlluminateServiceProvider
    {
        // routes
        //$this->loadRoutesFrom(realpath(__DIR__ . '/../routes/web.php'));
        // migrations
        //$this->loadMigrationsFrom(__DIR__ . '/path/to/migrations');
        // translations
        //$this->loadTranslationsFrom(realpath(__DIR__ . '/../resources/lang'), 'rocXolid');

        return $this;
    }

    /**
     * Expose config files and resources to be published.
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function publish(): IlluminateServiceProvider
    {
        // configuration files
        // php artisan vendor:publish --provider="Softworx\RocXolid\ServiceProvider" --tag="config" (--force to overwrite)
        $this->publishes([
            __DIR__ . '/../config/main.php' => config_path('rocXolid/main.php'),
        ], 'config');

        /*
        // language files
        // php artisan vendor:publish --provider="Softworx\RocXolid\ServiceProvider" --tag="language" (--force to overwrite)
        $this->publishes([
            __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/rocXolid'),
        ], 'language');
        */

        /*
        // views files
        // php artisan vendor:publish --provider="Softworx\RocXolid\ServiceProvider" --tag="views" (--force to overwrite)
        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/rocXolid'),
        ], 'views');
        */

        /*
        // assets files
        // php artisan vendor:publish --provider="Softworx\RocXolid\ServiceProvider" --tag="assets" (--force to overwrite)
        $this->publishes([
            __DIR__ . '/../../resources/assets' => public_path('vendor/rocXolid'),
        ], 'assets');
        */

        return $this;
    }

    /**
     * Bind contracts / facades, so they don't have to be added to config/app.php.
     *
     * Template:
     *      $this->app->bind(<SomeContract>::class, <SomeImplementation>::class);
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function bindContracts(): IlluminateServiceProvider
    {
        $this->app->singleton(
            Services\Contracts\ViewService::class,
            Services\ViewService::class
        );
        // @TODO: doesn't work
        $this->app->singleton(
            Illuminate\Contracts\Debug\ExceptionHandler::class,
            Exceptions\Handler::class
        );

        $this->app->singleton(
            Communication\Contracts\AjaxResponse::class,
            Communication\JsonAjaxResponse::class
        );

        // @TODO: use some kind of form service to build and get forms, the same for tables (and columns)
        /*
        $this->app->singleton(Services\Contracts\FormService::class, function ($app)
        {
            return new Services\FormService();
        });
        */

        return $this;
    }

    /**
     * Bind aliases, so they don't have to be added to config/app.php.
     *
     * Template:
     *      $loader->alias('<alias>', <Facade/Contract>::class);
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    private function bindAliases(AliasLoader $loader): IlluminateServiceProvider
    {
        // rocXolid
        $loader->alias('ViewHelper', Helpers\View::class);
        $loader->alias('RocXolidFormRequest', Http\Requests\FormRequest::class);
        $loader->alias('RocXolidRepositoryRequest', Http\Requests\RepositoryRequest::class);
        // third-party
        $loader->alias('Form', \Collective\Html\FormFacade::class);
        $loader->alias('Html', \Collective\Html\HtmlFacade::class);
        $loader->alias('InterventionImage', \Intervention\Image\Facades\Image::class);
        // DEV
        //$loader->alias('Debugbar', \Barryvdh\Debugbar\Facade::class);

        return $this;
    }
}
