<?php

namespace Softworx\RocXolid\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

/**
 * CRUD controller routes registrar.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo: refator & consider routes registration responsibility delegation to controllers
 */
class CrudRouterService
{
    protected $extra_routes = [];

    protected $name = null;
    protected $options = null;
    protected $controller = null;

    public static function create(string $name, string $controller, ?array $options = [])
    {
        return new static($name, $controller, $options);
    }

    private function __construct(string $name, string $controller, array $options)
    {
        $param = Str::slug($this->name, '_');

        $this->name = $name;
        $this->controller = $controller;
        $this->options = $options;

        $this
            ->registerPlatformRoutes($param)
            ->registerPackageRoutes($param);
    }

    private function registerPlatformRoutes(string $param): CrudRouterService
    {
        Route::post($this->name . '/search', [
            'as' => 'crud.' . $this->name . '.search',
            'uses' => $this->controller . '@search',
        ]);

        Route::get($this->name . '/table/{param}/order-by/{order_by_column}/{order_by_direction?}', [
            'as' => 'crud.' . $this->name . '.table-order',
            'uses' => $this->controller . '@tableOrderBy',
        ]);

        Route::post($this->name . '/table/{param}/filter', [
            'as' => 'crud.' . $this->name . '.table-filter',
            'uses' => $this->controller . '@tableFilter',
        ]);

        Route::post($this->name . sprintf('/repository/autocomplete/{%s?}', $param), [
            'as' => 'crud.' . $this->name . '.repository-autocomplete',
            'uses' => $this->controller . '@repositoryAutocomplete',
        ]);

        Route::post($this->name . sprintf('/form/reload/{%s?}', $param), [
            'as' => 'crud.' . $this->name . '.form-reload',
            'uses' => $this->controller . '@formReload',
        ]);

        Route::post($this->name . sprintf('/form/reload/group/{field_group}/{%s?}', $param), [
            'as' => 'crud.' . $this->name . '.form-reload-group',
            'uses' => $this->controller . '@formReloadGroup',
        ]);

        Route::post($this->name . sprintf('/form/validate/group/{field_group}/{%s?}', $param), [
            'as' => 'crud.' . $this->name . '.form-validate-group',
            'uses' => $this->controller . '@formValidateGroup',
        ]);

        Route::post($this->name . sprintf('/form-validate/field/{field}/{%s?}', $param), [
            'as' => 'crud.' . $this->name . '.form-validate-field',
            'uses' => $this->controller . '@formValidateField',
        ]);

        Route::post($this->name . sprintf('/{%s}/{relation}/reorder', $param), [
            'as' => 'crud.' . $this->name . '.reorder',
            'uses' => $this->controller . '@reorder',
        ]);

        Route::get($this->name . sprintf('/{%s}/translate/{lang}', $param), [
            'as' => 'crud.' . $this->name . '.translate-item',
            'uses' => $this->controller . '@translateItem',
        ]);

        Route::get($this->name . sprintf('/clone/{%s}', $param), [
            'as' => 'crud.' . $this->name . '.clone-confirm',
            'uses' => $this->controller . '@cloneConfirm',
        ]);

        Route::post($this->name . sprintf('/clone/{%s}', $param), [
            'as' => 'crud.' . $this->name . '.clone',
            'uses' => $this->controller . '@clone',
        ]);

        Route::get($this->name . sprintf('/destroy/{%s}', $param), [
            'as' => 'crud.' . $this->name . '.destroy-confirm',
            'uses' => $this->controller . '@destroyConfirm',
        ]);

        Route::get($this->name . sprintf('/{%s}/detach', $param), [
            'as' => 'crud.' . $this->name . '.detach',
            'uses' => $this->controller . '@detach',
        ]);

        Route::get($this->name . sprintf('/{%s}/toggle-pivot-data/{pivot_data}', $param), [
            'as' => 'crud.' . $this->name . '.toggle-pivot-data',
            'uses' => $this->controller . '@togglePivotData',
        ]);

        Route::get($this->name . sprintf('/{%s}/switch/enability', $param), [
            'as' => 'crud.' . $this->name . '.switch-enability',
            'uses' => $this->controller . '@switchEnability',
        ]);

        return $this;
    }

    protected function registerPackageRoutes(string $param): CrudRouterService
    {
        return $this;
    }

    /**
     * Register resource CRUD routes after all.
     *
     * @return void
     */
    public function __destruct()
    {
        $options_with_default_route_names = array_merge([
            'names' => [
                'index'     => 'crud.' . $this->name . '.index',
                'create'    => 'crud.' . $this->name . '.create',
                'store'     => 'crud.' . $this->name . '.store',
                'edit'      => 'crud.' . $this->name . '.edit',
                'update'    => 'crud.' . $this->name . '.update',
                'show'      => 'crud.' . $this->name . '.show',
                'clone'     => 'crud.' . $this->name . '.clone',
                'destroy'   => 'crud.' . $this->name . '.destroy',
            ],
        ], $this->options);

        Route::resource($this->name, $this->controller, $options_with_default_route_names);
    }

    // @todo: purpose & correctness?
    public function with($injectables): CrudRouterService
    {
        if (is_string($injectables)) {
            $this->extra_routes[] = 'with' . ucwords($injectables);
        } elseif (is_array($injectables)) {
            foreach ($injectables as $injectable) {
                $this->extra_routes[] = 'with' . ucwords($injectable);
            }
        } elseif ((new \ReflectionFunction($injectables))->isClosure()) {
            $this->extra_routes[] = $injectables;
        }

        return $this->registerExtraRoutes();
    }

    // @todo: purpose & correctness?
    private function registerExtraRoutes(): CrudRouterService
    {
        foreach ($this->extra_routes as $route) {
            if (is_string($route)) {
                $this->{$route}();
            } else {
                $route();
            }
        }

        return $this;
    }

    // @todo: purpose & correctness?
    public function __call($method, $parameters = null)
    {
        if (method_exists($this, $method)) {
            $this->{$method}($parameters);
        }
    }
}
