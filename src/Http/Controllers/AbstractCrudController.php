<?php

namespace Softworx\RocXolid\Http\Controllers;

use Auth;
// contracts
use Softworx\RocXolid\Contracts\Modellable;
use Softworx\RocXolid\Http\Controllers\Contracts\Crudable;
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Repositories\Contracts\Repositoryable;
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
// component contracts
use Softworx\RocXolid\Components\Contracts\Repositoryable as RepositoryableComponent;
use Softworx\RocXolid\Components\Contracts\Modellable as ModellableComponent;
// traits
use Softworx\RocXolid\Http\Controllers\Traits\Crudable as CrudableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\RepositoryOrderable as RepositoryOrderableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\RepositoryFilterable as RepositoryFilterableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\RepositoryAutocompleteable as RepositoryAutocompleteableTrait;
use Softworx\RocXolid\Http\Controllers\Traits\ItemsReorderderable as ItemsReorderderableTrait;
use Softworx\RocXolid\Repositories\Traits\Repositoryable as RepositoryableTrait; // @todo - toto by mal byt trait controlleru - refactorovat, podobne ako ostatne analogicke pouzitia traitov
use Softworx\RocXolid\Traits\Modellable as ModellableTrait;
// components
use Softworx\RocXolid\Components\Tables\CrudTable as CrudTableComponent;
use Softworx\RocXolid\Components\ModelViewers\CrudModelViewer as CrudModelViewerComponent;

/**
 * @todo - toto asi skor na styl ako je Softworx\RocXolid\DevKit\Console\Commands\Generate\Traits\Formable, pripadne to nejako vyuzit / upratat do traitu(ov)
 */
abstract class AbstractCrudController extends AbstractController implements Crudable, Repositoryable, Modellable // doplnit repository features traity / contracty
{
    use CrudableTrait;
    use ModellableTrait;
    use RepositoryableTrait;
    use RepositoryOrderableTrait;
    use RepositoryFilterableTrait;
    use RepositoryAutocompleteableTrait;
    use ItemsReorderderableTrait;
    //use AjaxTable;
    //use Revisions;
    //use ShowDetailsRow;
    //use SaveActions;

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

    public function userCan($method_group): bool
    {
        $permission = sprintf('\%s.%s', get_class($this), $method_group);

        if ($user = Auth::guard('rocXolid')->user()) {
            // @TODO - zmenit - len narychlo pre luminox
            return true;
            if ($user->id == 1) {
                return true;
            }

            foreach ($user->permissions as $extra_permission) {
                if (($extra_permission->controller_class == sprintf('\%s', static::class)) && ($extra_permission->controller_method_group == $method_group)) {
                    return true;
                } elseif (($method_group == 'read-only') && (($extra_permission->controller_class == sprintf('\%s', static::class)) && ($extra_permission->controller_method_group == 'write'))) {
                    return true;
                }
            }

            foreach ($user->roles as $role) {
                foreach ($role->permissions as $permission) {
                    if (($permission->controller_class == sprintf('\%s', static::class)) && ($permission->controller_method_group == $method_group)) {
                        return true;
                    } elseif (($method_group == 'read-only') && (($permission->controller_class == sprintf('\%s', static::class)) && ($permission->controller_method_group == 'write'))) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

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
            ->setRepository($repository);
    }

    public function getModelViewerComponent(CrudableModel $model): CrudModelViewerComponent
    {
        return (new CrudModelViewerComponent())
            ->setModel($model)
            ->setController($this);
    }
}
