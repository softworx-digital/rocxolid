<?php

namespace Softworx\RocXolid\Services;

use Route;

// @todo: refator
class CrudRouterService
{
    protected $extra_routes = [];

    protected $name = null;
    protected $options = null;
    protected $controller = null;

    public static function create($name, $controller, $options = [])
    {
        return new static($name, $controller, $options = []);
    }

    public function __construct($name, $controller, $options)
    {
        $this->name = $name;
        $this->controller = $controller;
        $this->options = $options;

        // CRUD routes for core features
        Route::match(['put', 'patch'], $this->name . '/image-upload/{id?}', [
            'as' => 'crud.' . $this->name . '.image-upload',
            'uses' => $this->controller . '@imageUpload',
        ]);

        Route::match(['put', 'patch'], $this->name . '/file-upload/{id?}', [
            'as' => 'crud.' . $this->name . '.file-upload',
            'uses' => $this->controller . '@fileUpload',
        ]);

        Route::post($this->name . '/search', [
            'as' => 'crud.' . $this->name . '.search',
            'uses' => $this->controller . '@search',
        ]);

        Route::get($this->name . '/repository-order-by/{param}/{order_by_column}/{order_by_direction?}', [
            'as' => 'crud.' . $this->name . '.repository-order',
            'uses' => $this->controller . '@repositoryOrderBy',
        ]);

        Route::post($this->name . '/repository-filter/{param}', [
            'as' => 'crud.' . $this->name . '.repository-filter',
            'uses' => $this->controller . '@repositoryFilter',
        ]);

        Route::post($this->name . '/repository-autocomplete/{id?}', [
            'as' => 'crud.' . $this->name . '.repository-autocomplete',
            'uses' => $this->controller . '@repositoryAutocomplete',
        ]);

        Route::post($this->name . '/repository-typeahead/{id?}', [
            'as' => 'crud.' . $this->name . '.repository-typeahead',
            'uses' => $this->controller . '@repositoryTypeahead',
        ]);

        Route::post($this->name . '/form-reload/{id?}', [
            'as' => 'crud.' . $this->name . '.formReload',
            'uses' => $this->controller . '@formReload',
        ]);

        Route::post($this->name . '/{id}/{relation}/reorder', [
            'as' => 'crud.' . $this->name . '.reorder',
            'uses' => $this->controller . '@reorder',
        ]);

        Route::get($this->name . '/{id}/translate/{lang}', [
            'as' => 'crud.' . $this->name . '.translateItem',
            'uses' => $this->controller . '@translateItem',
        ]);

        Route::get($this->name . '/{id}/clone', [
            'as' => 'crud.' . $this->name . '.cloneConfirm',
            'uses' => $this->controller . '@cloneConfirm',
        ]);

        Route::post($this->name . '/{id}/clone', [
            'as' => 'crud.' . $this->name . '.clone',
            'uses' => $this->controller . '@clone',
        ]);

        Route::get($this->name . '/{id}/destroyconfirm', [
            'as' => 'crud.' . $this->name . '.destroyConfirm',
            'uses' => $this->controller . '@destroyConfirm',
        ]);

        Route::get($this->name . '/{id}/detach', [
            'as' => 'crud.' . $this->name . '.detach',
            'uses' => $this->controller . '@detach',
        ]);

        Route::get($this->name . '/{id}/toggle-pivot-data/{pivot_data}', [
            'as' => 'crud.' . $this->name . '.togglePivotData',
            'uses' => $this->controller . '@togglePivotData',
        ]);
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

    public function with($injectables)
    {
        if (is_string($injectables)) {
            $this->extra_routes[] = 'with' . ucwords($injectables);
        } elseif (is_array($injectables)) {
            foreach ($injectables as $injectable) {
                $this->extra_routes[] = 'with' . ucwords($injectable);
            }
        } else {
            $reflection = new \ReflectionFunction($injectables);

            if ($reflection->isClosure()) {
                $this->extra_routes[] = $injectables;
            }
        }

        return $this->registerExtraRoutes();
    }

    private function registerExtraRoutes()
    {
        foreach ($this->extra_routes as $route) {
            if (is_string($route)) {
                $this->{$route}();
            } else {
                $route();
            }
        }
    }

    public function __call($method, $parameters = null)
    {
        if (method_exists($this, $method)) {
            $this->{$method}($parameters);
        }
    }
}
