<?php

namespace Softworx\RocXolid;

use Cache;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Contracts\Cache\Repository as IlluminateCacheRepository;
// @todo: not yet integrated, in use: \Barryvdh\TranslationManager\ManagerServiceProvider
// use Vsch\TranslationManager\Translator;


/**
 * rocXolid package service provider.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class ServiceProvider extends AbstractServiceProvider
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
        $this->app->register(Providers\ExtensionServiceProvider::class);
        $this->app->register(Providers\FacadeServiceProvider::class);

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
    }

    /**
     * Load routes, migrations, views and translations.
     *
     * @return \Softworx\RocXolid\AbstractServiceProvider
     */
    private function load(): AbstractServiceProvider
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
     * @return \Softworx\RocXolid\AbstractServiceProvider
     */
    private function publish(): AbstractServiceProvider
    {
        // configuration files
        // php artisan vendor:publish --provider="Softworx\RocXolid\ServiceProvider" --tag="config" (--force to overwrite)
        $this->publishes([
            __DIR__ . '/../config/main.php' => config_path('rocXolid/main.php'),
            __DIR__ . '/../config/validation.php' => config_path('rocXolid/validation.php'),
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
     * @return \Softworx\RocXolid\AbstractServiceProvider
     */
    private function bindContracts(): AbstractServiceProvider
    {
        // @todo: doesn't work for unknown reason
        $this->app->singleton(
            Illuminate\Contracts\Debug\ExceptionHandler::class,
            Exceptions\Handler::class
        );

        $this->app->singleton(
            Http\Responses\Contracts\AjaxResponse::class,
            Http\Responses\JsonAjaxResponse::class
        );

        $this->app->singleton(
            Rendering\Services\Contracts\RenderingService::class,
            Rendering\Services\RenderingService::class
        );

        $this->app->when(Rendering\Services\RenderingService::class)
            ->needs(IlluminateCacheRepository::class)
            ->give(function () {
                return Cache::store('array');
            });

        return $this
            ->bindRepositoriesContracts()
            ->bindFormsContracts()
            ->bindTablesContracts();
    }

    /**
     * Bind contracts related to repositories.
     *
     * Template:
     *      $this->app->bind(<SomeContract>::class, <SomeImplementation>::class);
     *
     * @return \Softworx\RocXolid\AbstractServiceProvider
     */
    private function bindRepositoriesContracts(): AbstractServiceProvider
    {
        $this->app->bind(
            Repositories\Contracts\Repository::class,
            Repositories\CrudRepository::class
        );

        return $this;
    }

    /**
     * Bind contracts related to forms.
     *
     * Template:
     *      $this->app->bind(<SomeContract>::class, <SomeImplementation>::class);
     *
     * @return \Softworx\RocXolid\AbstractServiceProvider
     */
    private function bindFormsContracts(): AbstractServiceProvider
    {
        $this->app->singleton(
            Forms\Services\Contracts\FormService::class,
            Forms\Services\FormService::class
        );

        $this->app->singleton(
            Forms\Builders\Contracts\FormBuilder::class,
            Forms\Builders\FormBuilder::class
        );

        $this->app->singleton(
            Forms\Builders\Contracts\FormFieldBuilder::class,
            Forms\Builders\FormFieldBuilder::class
        );

        $this->app->singleton(
            Forms\Builders\Contracts\FormFieldFactory::class,
            Forms\Builders\FormFieldFactory::class
        );

        return $this;
    }

    /**
     * Bind contracts related to tables.
     *
     * Template:
     *      $this->app->bind(<SomeContract>::class, <SomeImplementation>::class);
     *
     * @return \Softworx\RocXolid\AbstractServiceProvider
     */
    private function bindTablesContracts(): AbstractServiceProvider
    {
        $this->app->singleton(
            Tables\Services\Contracts\TableService::class,
            Tables\Services\TableService::class
        );

        $this->app->singleton(
            Tables\Builders\Contracts\TableBuilder::class,
            Tables\Builders\TableBuilder::class
        );

        $this->app->singleton(
            Tables\Builders\Contracts\TableFilterBuilder::class,
            Tables\Builders\TableFilterBuilder::class
        );

        $this->app->singleton(
            Tables\Builders\Contracts\TableColumnBuilder::class,
            Tables\Builders\TableColumnBuilder::class
        );

        $this->app->singleton(
            Tables\Builders\Contracts\TableButtonBuilder::class,
            Tables\Builders\TableButtonBuilder::class
        );

        return $this;
    }

    /**
     * Bind aliases, so they don't have to be added to config/app.php.
     *
     * Template:
     *      $loader->alias('<alias>', <Facade/Contract>::class);
     *
     * @return \Softworx\RocXolid\AbstractServiceProvider
     */
    private function bindAliases(AliasLoader $loader): AbstractServiceProvider
    {
        // rocXolid
        $loader->alias('ViewHelper', Helpers\View::class);
        $loader->alias('Package', Facades\Package::class);
        // third-party
        $loader->alias('Form', \Collective\Html\FormFacade::class);
        $loader->alias('Html', \Collective\Html\HtmlFacade::class);
        $loader->alias('InterventionImage', \Intervention\Image\Facades\Image::class);

        return $this;
    }
}
