<?php

namespace Softworx\RocXolid\Http\Controllers;

// rocXolid contracts
use Softworx\RocXolid\Contracts\Modellable;
use Softworx\RocXolid\Http\Controllers\Contracts\Crudable;
use Softworx\RocXolid\Http\Controllers\Contracts\Permissionable;
use Softworx\RocXolid\Http\Controllers\Contracts\Dashboardable;
use Softworx\RocXolid\Repositories\Contracts\Repositoryable;
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
// rocXolid component contracts
use Softworx\RocXolid\Components\Contracts\Repositoryable as RepositoryableComponent;
use Softworx\RocXolid\Components\Contracts\Modellable as ModellableComponent;
// rocXolid traits
use Softworx\RocXolid\Http\Controllers\Traits\Crudable as CrudableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\Permissionable as PermissionableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\Dashboardable as DashboardableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\RepositoryOrderable as RepositoryOrderableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\RepositoryFilterable as RepositoryFilterableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\RepositoryAutocompleteable as RepositoryAutocompleteableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\ItemsReorderderable as ItemsReorderderableTrait;
use Softworx\RocXolid\Repositories\Traits\Repositoryable as RepositoryableTrait;
use Softworx\RocXolid\Traits\Modellable as ModellableTrait;
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

    public function getRoute($route_action, ...$params)
    {
        $action = sprintf('\%s@%s', get_class($this), $route_action);
        $model = array_shift($params);

        if (is_array(reset($params))) {
            $params = reset($params);
        }

        return is_null($model) ? action($action, $params) : action($action, ['model' => $model] + $params);
    }

    public function getRepositoryComponent(Repository $repository): RepositoryableComponent
    {
        return (new CrudTableComponent())
            ->setRepository($repository)
            ->setTranslationPackage($this->provideTranslationPackage())
            ->setTranslationParam($this->provideTranslationParam());
    }

    public function getModelViewerComponent(CrudableModel $model): CrudModelViewerComponent
    {
        return (new CrudModelViewerComponent())
            ->setModel($model)
            ->setController($this)
            ->setTranslationPackage($this->provideTranslationPackage())
            ->setTranslationParam($this->provideTranslationParam());
    }
}
