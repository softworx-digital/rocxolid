<?php

namespace Softworx\RocXolid\Http\Controllers;

use Illuminate\Support\Str;
// rocXolid contracts
use Softworx\RocXolid\Contracts\Modellable;
use Softworx\RocXolid\Http\Controllers\Contracts\Crudable;
use Softworx\RocXolid\Http\Controllers\Contracts\Permissionable;
use Softworx\RocXolid\Http\Controllers\Contracts\Dashboardable;
use Softworx\RocXolid\Repositories\Contracts\Repositoryable; // @todo: put to controller contracts? check occurences and usage
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
// rocXolid component contracts
use Softworx\RocXolid\Components\Contracts\Repositoryable as RepositoryableComponent;
use Softworx\RocXolid\Components\Contracts\Modellable as ModellableComponent;
// rocXolid traits
use Softworx\RocXolid\Traits\Modellable as ModellableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\Crudable as CrudableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\Permissionable as PermissionableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\Dashboardable as DashboardableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\RepositoryOrderable as RepositoryOrderableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\RepositoryFilterable as RepositoryFilterableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\RepositoryAutocompleteable as RepositoryAutocompleteableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\ItemsReorderderable as ItemsReorderderableTrait;
use Softworx\RocXolid\Repositories\Traits\Repositoryable as RepositoryableTrait; // @todo: put to controller traits? check occurences and usage
// rocXolid components
use Softworx\RocXolid\Components\Tables\CrudTable as CrudTableComponent;
use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer as CrudModelViewerComponent;

/**
 * Base rocXolid controller for CRUD (and associated) operations.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo: add contracts to repository features traits
 */
abstract class AbstractCrudController extends AbstractController implements Permissionable, Crudable, Dashboardable, Repositoryable, Modellable
{
    use CrudableTrait;
    use PermissionableTrait;
    use DashboardableTrait;
    use ModellableTrait;
    use RepositoryableTrait;
    use RepositoryOrderableTrait;
    use RepositoryFilterableTrait;
    use RepositoryAutocompleteableTrait;
    use ItemsReorderderableTrait;

    /**
     * <repository-param> => <repository-class>
     *
     */
    protected static $repository_param_class = [];
    /**
     * <controller-action> => <repository-param>
     * or
     * <controller-action>.<section> => <repository-param>
     *
     */
    protected $repository_mapping = [];
    /**
     * <controller-action> => <form-param>
     * or
     * <controller-action>.<section> => <form-param>
     *
     */
    protected $form_mapping = [
        'create' => 'create',
        'store' => 'create',
        'edit' => 'update',
        'update' => 'update',
    ];

    /**
     * Dynamically create route for given controller action.
     * Set model as a first parameter to the route if given.
     * 
     * @param string $route_action
     * @return string
     */
    public function getRoute(string $route_action, ...$params):string
    {
        $action = sprintf('\%s@%s', get_class($this), $route_action);
        $model = array_shift($params);

        if (!empty($params)) {
            $params = reset($params);
        }

        return is_null($model) ? action($action, $params) : action($action, [$model] + $params);
    }

    /**
     * Retrieve repository component to show.
     * 
     * @param \Softworx\RocXolid\Repositories\Contracts\Repository $repository
     * @return \Softworx\RocXolid\Components\Contracts\Repositoryable
     */
    public function getRepositoryComponent(Repository $repository): RepositoryableComponent
    {
        return CrudTableComponent::build($this, $this)
            ->setRepository($repository);
    }

    /**
     * Retrieve model viewer component to show.
     * 
     * @param \Softworx\RocXolid\Models\Contracts\Crudable $model
     * @return \Softworx\RocXolid\Components\ModelViewers\CrudModelViewer
     */
    public function getModelViewerComponent(CrudableModel $model): CrudModelViewerComponent
    {
        return CrudModelViewerComponent::build($this, $this)
            ->setModel($model)
            ->setController($this);
    }

    /**
     * Naively guess the translation param for components based on controllers namespace.
     * 
     * @return string
     */
    protected function guessTranslationParam(): ?string
    {
        $reflection = new \ReflectionClass($this);
        $param = last(explode('\\', $reflection->getNamespaceName()));

        return Str::kebab($param);
    }
}
