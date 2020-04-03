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
// rocXolid table traits
use Softworx\RocXolid\Tables\Traits\Buildable;
use Softworx\RocXolid\Tables\Traits\Columnable;
use Softworx\RocXolid\Tables\Traits\Buttonable;
use Softworx\RocXolid\Tables\Traits\Orderable;
use Softworx\RocXolid\Tables\Traits\Filterable;
use Softworx\RocXolid\Tables\Traits\Paginationable;
// rocXolid table button types
use Softworx\RocXolid\Tables\Buttons\Type\ButtonAnchor;

/**
 * Model instances data table.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractCrudTable implements Table, Requestable
{
    use RequestableTrait;
    use Buildable;
    use Columnable;
    use Buttonable;
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

    public function init(): Table
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

    public function processTableOptions(): Table
    {
        $this->mergeOptions([
            'component' => [
                'id' => ViewHelper::domId($this, 'table')
            ]
        ]);

        return $this;
    }

    public function setCustomOptions($custom_options): Table
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
    protected function setRoute($route_name): Table
    {
        $this->mergeOptions([
            'component' => [
                //'url' => $this->makeRoute($route_name)
                'url' => '__placeholder__'
            ]
        ]);

        return $this;
    }

    protected function setClass($class): Table
    {
        $this->mergeOptions([
            'component' => [
                'class' => $class
            ]
        ]);

        return $this;
    }

    protected function setTemplate($template): Table
    {
        $this->mergeOptions([
            'component' => [
                'template' => $template
            ]
        ]);

        return $this;
    }
}
