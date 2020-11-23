<?php

namespace Softworx\RocXolid\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Routing\Controller as IlluminateController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// rocXolid service contracts
use Softworx\RocXolid\Services\Contracts\ConsumerService;
// rocXolid contracts
use Softworx\RocXolid\Contracts\Responseable;
use Softworx\RocXolid\Services\Contracts\ServiceConsumer;
use Softworx\RocXolid\Contracts\TranslationPackageProvider;
use Softworx\RocXolid\Contracts\TranslationParamProvider;
// rocXolid traits
use Softworx\RocXolid\Traits\Responseable as ResponseableTrait;
use Softworx\RocXolid\Traits\TranslationPackageProvider as TranslationPackageProviderTrait;
use Softworx\RocXolid\Traits\TranslationParamProvider as TranslationParamProviderTrait;
// rocXolid controller traits
use Softworx\RocXolid\Http\Controllers\Traits\Utils;

/**
 * Base rocXolid controller.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractController extends IlluminateController implements Responseable, ServiceConsumer, TranslationPackageProvider, TranslationParamProvider
{
    use ResponseableTrait;
    use AuthorizesRequests;
    use TranslationPackageProviderTrait;
    use TranslationParamProviderTrait;
    use Utils\CreatesRoutes;
    use Utils\Translates;

    /**
     * Default services used by controller.
     *
     * @var array
     */
    protected $default_services = [];

    /**
     * Extra services class definition to be specified in specific controller class.
     *
     * @var array
     */
    protected $extra_services = [];

    /**
     * Service accessor methods binding container.
     *
     * @var array
     */
    private $service_accessors = [];

    /**
     * Place to do some controller specific initialization.
     *
     * @return \Softworx\RocXolid\Http\Controllers\AbstractController
     */
    protected function init(): AbstractController
    {
        return $this;
    }

    /**
     * Bind services to controller and dynamically create methods to access them.
     *
     * @return \Softworx\RocXolid\Http\Controllers\AbstractController
     */
    protected function bindServices(): AbstractController
    {
        $this->getServices()->each(function ($service_type) {
            $service = app($service_type);

            $method = lcfirst((new \ReflectionClass($service))->getShortName());

            if (property_exists($this, $method) || isset($this->service_accessors[$method])) {
                throw new \RuntimeException(sprintf('Controller [%s] already has property or service accessor [%s] assigned', get_class($this), $method));
            }

            if ($service instanceof ConsumerService) {
                $service->setConsumer($this);
            }

            $this->service_accessors[$method] = function () use ($service) {
                return $service;
            };
        });

        return $this;
    }

    /**
     * Retrieve services to be bound.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getServices(): Collection
    {
        return collect($this->default_services)->merge($this->extra_services);
    }

    /**
     * Dynamically create route for given controller action.
     * Set model as a first parameter to the route if given.
     *
     * @param string $route_action
     * @param mixed $params,...
     * @return string
     */
    public function getRoute(string $route_action, ...$params): string
    {
        $action = sprintf('\%s@%s', get_class($this), $route_action);
        $action_params = [];

        array_walk($params, function ($param) use (&$action_params) {
            if (is_array($param)) {
                $action_params += $param;
            } else {
                $action_params[] = $param;
            }
        });

        return action($action, $action_params);
    }

    /**
     * Naively guess the translation param for components based on controllers namespace.
     *
     * @return string
     */
    protected function guessTranslationParam(): ?string
    {
        // $param = last(explode('\\', (new \ReflectionClass($this))->getNamespaceName()));
        $param = Str::afterLast((new \ReflectionClass($this))->getNamespaceName(), 'Controllers\\');

        return collect(explode('\\', $param))->transform(function (string $param) {
            return Str::kebab($param);
        })->join('.');
    }

    /**
     * {@inheritDoc}
     */
    public function __call($method, $args)
    {
        if (isset($this->service_accessors[$method]) && is_callable($this->service_accessors[$method])) {
            return call_user_func_array($this->service_accessors[$method], $args);
        }

        return parent::__call($method, $args);
    }
}
