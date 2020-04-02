<?php

namespace Softworx\RocXolid\Tables;

use Illuminate\Support\Collection;
// rocXolid utils
use Softworx\RocXolid\Helpers\View as ViewHelper;
use Softworx\RocXolid\Http\Requests\CrudRequest;
// rocXolid contracts
use Softworx\RocXolid\Contracts\Requestable;
use Softworx\RocXolid\Tables\Contracts\Table;
use Softworx\RocXolid\Models\Contracts\Crudable as CrudableModel;
// rocXolid general traits
use Softworx\RocXolid\Traits\Controllable;
use Softworx\RocXolid\Traits\MethodOptionable;
use Softworx\RocXolid\Traits\Paramable;
use Softworx\RocXolid\Traits\Requestable as RequestableTrait;
// rocXolid repository traits
use Softworx\RocXolid\Tables\Traits\Buildable;
use Softworx\RocXolid\Tables\Traits\Orderable;
use Softworx\RocXolid\Tables\Traits\Filterable;
use Softworx\RocXolid\Tables\Traits\Paginationable;
// rocXolid column types
use Softworx\RocXolid\Tables\Columns\Type\ButtonAnchor;

/**
 * @todo: subject to refactoring, (at least) decompose to:
 *  - repository that handles model data operations
 *  - table that is responsible for showing data
 */
abstract class AbstractCrudTable implements Table, Requestable
{
    use RequestableTrait;
    use Buildable;
    use Orderable;
    use Filterable;
    use Paginationable;
    use Controllable;
    use Paramable;
    use MethodOptionable;

    /**
     * @var CrudableModel
     */
    protected $model;

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

    public function getSessionParam(string $param = 'default')
    {
        return sprintf('%s-%s', md5(get_class($this)), $param);
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
