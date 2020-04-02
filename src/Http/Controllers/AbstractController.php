<?php

namespace Softworx\RocXolid\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Routing\Controller as IlluminateController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// rocXolid contracts
use Softworx\RocXolid\Contracts\TranslationPackageProvider;
use Softworx\RocXolid\Contracts\TranslationParamProvider;
// rocXolid traits
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
abstract class AbstractController extends IlluminateController implements TranslationPackageProvider, TranslationParamProvider
{
    use AuthorizesRequests;
    use TranslationPackageProviderTrait;
    use TranslationParamProviderTrait;
    use Utils\CreatesRoutes;
    use Utils\Translates;

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
        $param = last(explode('\\', (new \ReflectionClass($this))->getNamespaceName()));

        return Str::kebab($param);
    }
}
