<?php

namespace Softworx\RocXolid\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Routing\Route;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
// rocXolid controller contracts
use Softworx\RocXolid\Http\Controllers\Contracts\Crudable;

/**
 * CRUD controller routes registrar.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo refator & consider routes registration responsibility delegation to controllers
 * @todo or create custom ResourceRegistrar (extends Illuminate\Routing\ResourceRegistrar)
 */
class CrudRouterService
{
    protected $extra_routes = [];

    protected $name = null;
    protected $controller = null;
    protected $options = null;
    protected $param = null;

    public static function create(string $name, string $controller, ?array $options = [], ?string $param = null)
    {
        return new static($name, $controller, $options, $param);
    }

    public static function requestToRoute(Request $request): ?Route
    {
        $route = collect(RouteFacade::getRoutes())->first(function (Route $route) use ($request) {
            return $route->matches($request);
        });

        return $route ? $route->bind($request) : null;
    }

    public static function backLink(Model $model): ?array
    {
        $url = request()->headers->get('referer');

        if (is_null($url)) {
            return null;
        }

        $request = request()->create($url);

        $route = self::requestToRoute($request);

        if (is_null($route)) {
            return null;
        }

        $controller = $route->getController();

        if (!($controller instanceof Crudable)) {
            return null;
        }

        $repository = $route->getController()->getRepository();
        $prev_model = null;

        collect($route->parameters())->each(function (string $key, string $param) use ($repository, &$prev_model) {
            // @todo quite a naive assumption, better approach? couldn't find a way to resolve the model from bound route
            if (!$prev_model && is_numeric($key)) {
                $prev_model = $repository->find($key);
            }
        });

        $prev_model = $prev_model ?? $repository->getModel();

        if ($prev_model->is($model)) {
            return null;
        }

        return [ $prev_model, $url ];
    }

    private function __construct(string $name, string $controller, array $options, ?string $param)
    {
        $this->name = $name;
        $this->controller = $controller;
        $this->options = $options;
        $this->param = $param ?? Str::slug(str_replace('/', '_', $this->name), '_');

        $this
            ->registerPlatformRoutes()
            ->registerPackageRoutes($this->param);
    }

    private function registerPlatformRoutes(): CrudRouterService
    {
        RouteFacade::post($this->name . '/search', [
            'as' => 'crud.' . $this->name . '.search',
            'uses' => $this->controller . '@search',
        ]);

        RouteFacade::get($this->name . '/table/{param}/order-by/{order_by_column}/{order_by_direction?}', [
            'as' => 'crud.' . $this->name . '.table-order',
            'uses' => $this->controller . '@tableOrderBy',
        ]);

        RouteFacade::post($this->name . '/table/{param}/filter', [
            'as' => 'crud.' . $this->name . '.table-filter',
            'uses' => $this->controller . '@tableFilter',
        ]);

        RouteFacade::post($this->name . '/table/{param}/autocomplete/{filter}', [
            'as' => 'crud.' . $this->name . '.filter-autocomplete',
            'uses' => $this->controller . '@tableFilterAutocomplete',
        ]);

        RouteFacade::post($this->name . '/form/{param}/autocomplete/{field}', [
            'as' => 'crud.' . $this->name . '.field-autocomplete',
            'uses' => $this->controller . '@formFieldAutocomplete',
        ]);

        RouteFacade::post($this->name . sprintf('/form/reload/{%s?}', $this->param), [
            'as' => 'crud.' . $this->name . '.form-reload',
            'uses' => $this->controller . '@formReload',
        ]);

        RouteFacade::post($this->name . sprintf('/form/reload/group/{field_group}/{%s?}', $this->param), [
            'as' => 'crud.' . $this->name . '.form-reload-group',
            'uses' => $this->controller . '@formReloadGroup',
        ]);

        RouteFacade::post($this->name . sprintf('/form/validate/group/{field_group}/{%s?}', $this->param), [
            'as' => 'crud.' . $this->name . '.form-validate-group',
            'uses' => $this->controller . '@formValidateGroup',
        ]);

        RouteFacade::post($this->name . sprintf('/form-validate/field/{field}/{%s?}', $this->param), [
            'as' => 'crud.' . $this->name . '.form-validate-field',
            'uses' => $this->controller . '@formValidateField',
        ]);

        RouteFacade::post($this->name . sprintf('/{%s}/{relation}/reorder', $this->param), [
            'as' => 'crud.' . $this->name . '.reorder',
            'uses' => $this->controller . '@reorder',
        ]);

        RouteFacade::get($this->name . sprintf('/{%s}/translate/{lang}', $this->param), [
            'as' => 'crud.' . $this->name . '.translate-item',
            'uses' => $this->controller . '@translateItem',
        ]);

        RouteFacade::get($this->name . sprintf('/clone/{%s}', $this->param), [
            'as' => 'crud.' . $this->name . '.clone-confirm',
            'uses' => $this->controller . '@cloneConfirm',
        ]);

        RouteFacade::post($this->name . sprintf('/clone/{%s}', $this->param), [
            'as' => 'crud.' . $this->name . '.clone',
            'uses' => $this->controller . '@clone',
        ]);

        RouteFacade::get($this->name . sprintf('/destroy/{%s}', $this->param), [
            'as' => 'crud.' . $this->name . '.destroy-confirm',
            'uses' => $this->controller . '@destroyConfirm',
        ]);

        RouteFacade::get($this->name . sprintf('/{%s}/detach', $this->param), [
            'as' => 'crud.' . $this->name . '.detach',
            'uses' => $this->controller . '@detach',
        ]);

        RouteFacade::get($this->name . sprintf('/{%s}/toggle-pivot-data/{pivot_data}', $this->param), [
            'as' => 'crud.' . $this->name . '.toggle-pivot-data',
            'uses' => $this->controller . '@togglePivotData',
        ]);

        RouteFacade::get($this->name . sprintf('/{%s}/switch/enability', $this->param), [
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

        RouteFacade::resource($this->name, $this->controller, $options_with_default_route_names);
    }

    // @todo purpose & correctness?
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

    // @todo purpose & correctness?
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

    // @todo purpose & correctness?
    public function __call($method, $parameters = null)
    {
        if (method_exists($this, $method)) {
            $this->{$method}($parameters);
        }
    }
}
