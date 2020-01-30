<?php

namespace Softworx\RocXolid\Repositories;

use App;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Scope;
// relations
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
// rocXolid utils
use Softworx\RocXolid\Helpers\View as ViewHelper;
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid contracts
use Softworx\RocXolid\Contracts\Requestable;
use Softworx\RocXolid\Repositories\Contracts\Repository;
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
// rocXolid general traits
use Softworx\RocXolid\Traits\Controllable;
use Softworx\RocXolid\Traits\MethodOptionable;
use Softworx\RocXolid\Traits\Paramable;
use Softworx\RocXolid\Traits\EventDispatchable;
use Softworx\RocXolid\Traits\Requestable as RequestableTrait;
// rocXolid model scopes
use Softworx\RocXolid\Models\Scopes\Owned as OwnedScope;
// rocXolid repository traits
use Softworx\RocXolid\Repositories\Traits\Buildable;
use Softworx\RocXolid\Repositories\Traits\Orderable;
use Softworx\RocXolid\Repositories\Traits\Filterable;
use Softworx\RocXolid\Repositories\Traits\Paginationable;
use Softworx\RocXolid\Repositories\Traits\Columnable;
use Softworx\RocXolid\Repositories\Traits\Buttonable;
use Softworx\RocXolid\Forms\Traits\Formable;
// rocXolid column types
use Softworx\RocXolid\Repositories\Columns\Type\ButtonAnchor;

/**
 *
 */
abstract class AbstractCrudRepository implements Repository, Requestable
{
    use RequestableTrait;
    use Buildable;
    use Orderable;
    use Filterable;
    use Paginationable;
    use Columnable;
    use Buttonable;
    use Controllable;
    use Paramable;
    use MethodOptionable;
    use EventDispatchable;
    use Formable;

    /**
     * @var CrudRequest
     */
    protected $request;

    /**
     * @var CrudableModel
     */
    protected $model;

    /**
     * @var \Illuminate\Database\Query\Builder
     */
    protected $query;

    /**
     * @var array
     */
    protected $with_scopes = [
        OwnedScope::class,
    ];

    /**
     * @var array
     */
    protected $without_scopes = [];

    protected $repo_options = [];

    protected $filters = [];

    protected $buttons = [
        'show' => [
            'type' => ButtonAnchor::class,
            'options' => [
                'label' => [
                    'icon' => 'fa fa-window-maximize',
                ],
                'attributes' => [
                    'class' => 'btn btn-info btn-sm margin-right-no',
                    'title-key' => 'show',
                ],
                'policy-ability' => 'view',
                'action' => 'show',
            ],
        ],
        'show-modal' => [
            'type' => ButtonAnchor::class,
            'options' => [
                'ajax' => true,
                'label' => [
                    'icon' => 'fa fa-window-restore',
                ],
                'attributes' => [
                    'class' => 'btn btn-info btn-sm margin-right-no',
                    'title-key' => 'show-modal',
                ],
                'policy-ability' => 'view',
                'action' => 'show',
            ],
        ],
        'edit' => [
            'type' => ButtonAnchor::class,
            'options' => [
                'label' => [
                    'icon' => 'fa fa-pencil',
                ],
                'attributes' => [
                    'class' => 'btn btn-primary btn-sm margin-right-no',
                    'title-key' => 'edit',
                ],
                'policy-ability' => 'update',
                'action' => 'edit',
            ],
        ],/*
        'edit-ajax' => [
            'type' => ButtonAnchor::class,
            'options' => [
                'ajax' => true,
                'label' => [
                    'title-key' => 'Edit AJAX',
                ],
                'attributes' => [
                    'class' => 'btn btn-primary btn-sm',
                ],
                'policy-ability' => 'edit',
            ],
        ],
        'delete' => [
            'type' => ButtonAnchor::class,
            'options' => [
                'label' => [
                    'title-key' => 'Delete',
                ],
                'attributes' => [
                    'class' => 'btn btn-danger btn-sm'
                ],
                'policy-ability' => 'destroyConfirm',
            ],
        ],*/
        'delete-ajax' => [
            'type' => ButtonAnchor::class,
            'options' => [
                'ajax' => true,
                'label' => [
                    'icon' => 'fa fa-trash',
                ],
                'attributes' => [
                    'class' => 'btn btn-danger btn-sm margin-right-no',
                    'title-key' => 'delete',
                ],
                'policy-ability' => 'delete',
                'action' => 'destroyConfirm',
            ],
        ],
    ];

    public function init(): Repository
    {
        return $this;
    }

    public function withScops(Scope $scope): Repository
    {
        $this->with_scopes[] = $scope;

        return $this;
    }

    public function withoutScope(Scope $scope): Repository
    {
        $this->without_scopes[] = $scope;

        return $this;
    }

    public function getModel(): CrudableModel
    {
        if (is_null($this->model)) {
            $this->model = $this->makeModel();
        }

        return $this->model;
    }

    public function makeModel(): CrudableModel
    {
        return App::make($this->getController()->getModelClass());
    }

    public function getQuery(): EloquentBuilder
    {
        if (is_null($this->query)) {
            $this->query = $this->getModel()->query();
        }

        return $this->query;
    }

    public function getRoute($route_action, $model = null, $params = [])
    {
        if ($route_action == 'order') {
            return $this->getController()->getRoute('repositoryOrderBy', $model, [
                'param' => $this->getParam(),
                'order_by_column' => $params['order_by']['column'],
                'order_by_direction' => $params['order_by']['direction'],
            ]);
        }

        if ($route_action == 'filter') {
            return $this->getController()->getRoute('repositoryFilter', $model, [
                'param' => $this->getParam(),
            ]);
        }

        return $this->getController()->getRoute($route_action, $model, $params);
    }

    public function processRepositoryOptions(): Repository
    {
        $this->setOptions($this->repo_options);
        $this->mergeOptions([
            'component' => [
                'id' => ViewHelper::domId($this, 'repository')
            ]
        ]);

        return $this;
    }

    public function setCustomOptions($custom_options): Repository
    {
        $this->mergeOptions($custom_options);

        return $this;
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this
            ->applyScopes()
            ->applyOrder()
            ->applyFilters()
            ->applyIntenalFilters()
            ->get($columns);
    }

    public function count(): int
    {
        return $this
            ->applyScopes()
            ->applyOrder()
            ->applyFilters()
            ->applyIntenalFilters()
            ->count();
    }

    public function find(int $id, array $columns = ['*']): CrudableModel
    {
        return $this
            ->applyScopes()
            ->applyIntenalFilters()
            ->find($id, $columns);
    }

    public function findOrFail(int $id, array $columns = ['*']): CrudableModel
    {
        return $this
            ->applyScopes()
            ->applyIntenalFilters()
            ->findOrFail($id, $columns);
    }

    public function findBy(string $attribute, $value, array $columns = ['*']): CrudableModel
    {
        return $this
            ->applyScopes()
            ->applyIntenalFilters()
            ->where($attribute, '=', $value)
            ->first($columns);
    }

    protected function applyIntenalFilters(): EloquentBuilder
    {
        $query = $this->getQuery();

        return $query;
    }

    protected function applyScopes(): Repository
    {
        collect($this->with_scopes)->each(function ($scope) {
            $this->query = $this->getQuery()->withGlobalScope($scope, app($scope));
        });

        $this->query = $this->getQuery()->withoutGlobalScopes($this->without_scopes);

        return $this;
    }

    public function createModel(array $data): CrudableModel
    {
        return $this
            ->getModel()
            ->create($data);
    }

    public function update(array $data, int $id, string $attribute = 'id'): CrudableModel
    {
        return $this
            ->getQuery()
            ->where($attribute, '=', $id)
            ->update($data);
    }

    public function updateModel(array $data, CrudableModel $model, $action): CrudableModel
    {
        $model
            ->fill($data, $action)
            ->fillCustom($data, $action)
            ->resolvePolymorphism($data, $action)
            ->beforeSave($data, $action)
            ->save();

        $model
            ->fillRelationships($data, $action)
            ->afterSave($data, $action);

        return $model;
    }

    // @todo: what's this
    public function delete(array $id) // @todo: missing return type
    {
        return $this->getModel()->canBeDeleted() && $this->getModel()->destroy($id);
    }

    // @todo: and this
    public function deleteModel(CrudableModel $model): CrudableModel
    {
        if ($model->canBeDeleted()) {
            $model
                ->beforeDelete()
                ->delete();
            $model
                ->afterDelete();
        }

        return $model;
    }

    public function getSessionParam(string $param = 'default')
    {
        return sprintf('%s-%s', md5(get_class($this)), $param);
    }

    protected function getFormElementClass()
    {
        return $this->getController()->getModelClass();
    }
    // @todo: better put this in some definition class
    protected function getFiltersDefinition()
    {
        return $this->adjustFiltersDefinition($this->filters);
    }

    protected function adjustFiltersDefinition($filters)
    {
        return $filters;
    }

    protected function getColumnsDefinition()
    {
        return $this->adjustColumnsDefinition($this->columns);
    }

    protected function adjustColumnsDefinition($columns)
    {
        return $columns;
    }

    protected function getButtonsDefinition()
    {
        return $this->adjustButtonsDefinition($this->buttons);
    }

    protected function adjustButtonsDefinition($buttons)
    {
        return $buttons;
    }

    // @todo: component building options - better put this in a trait
    protected function setRoute($route_name): Repository
    {
        $this->mergeOptions([
            'component' => [
                //'url' => $this->makeRoute($route_name)
                'url' => '__placeholder__'
            ]
        ]);

        return $this;
    }

    protected function setClass($class): Repository
    {
        $this->mergeOptions([
            'component' => [
                'class' => $class
            ]
        ]);

        return $this;
    }

    protected function setTemplate($template): Repository
    {
        $this->mergeOptions([
            'component' => [
                'template' => $template
            ]
        ]);

        return $this;
    }
}
