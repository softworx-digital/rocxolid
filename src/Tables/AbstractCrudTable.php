<?php

namespace Softworx\RocXolid\Tables;

use Illuminate\Pagination\LengthAwarePaginator;
// rocXolid utils
use Softworx\RocXolid\Helpers\View as ViewHelper;
// rocXolid repository contracts
use Softworx\RocXolid\Repositories\Contracts\Repository;
// rocXolid contracts
use Softworx\RocXolid\Tables\Contracts\Table;
// rocXolid general traits
use Softworx\RocXolid\Traits as rxTraits;
// rocXolid table button types
use Softworx\RocXolid\Tables\Buttons\Type as ButtonType;

/**
 * Model instances data table.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractCrudTable implements Table
{
    use rxTraits\Paramable;
    use rxTraits\Requestable;
    use rxTraits\Controllable;
    use rxTraits\MethodOptionable;
    use Traits\Buildable;
    use Traits\Columnable;
    use Traits\Buttonable;
    use Traits\Scopeable;
    use Traits\Orderable;
    use Traits\Filterable;
    use Traits\Paginationable;

    /**
     * Table repository data holder.
     *
     * @var array
     */
    protected $data;

    /**
     * Table search filters definition.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Table columns definition.
     *
     * @var array
     */
    protected $columns = [];

    /**
     * Table row buttons definition.
     *
     * @var array
     */
    protected $buttons = [
        'show' => [
            'type' => ButtonType\ButtonAnchor::class,
            'options' => [
                'label' => [
                    'icon' => 'fa fa-eye',
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
            'type' => ButtonType\ButtonAnchor::class,
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
            'type' => ButtonType\ButtonAnchor::class,
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
            'type' => ButtonType\ButtonAnchor::class,
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

    /**
     * Constructor
     *
     * @param string $param Table parameter serves as reference to better identify the table eg. in session key creation.
     */
    public function __construct(string $param)
    {
        $this->setParam($param);
    }

    /**
     * {@inheritDoc}
     */
    public function init(): Table
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getData(): LengthAwarePaginator
    {
        if (!isset($this->data)) {
            $data = $this->initRepository()
                ->paginate($this->getCurrentPage(), $this->getPerPage())
                ->withPath($this->getPaginatorRoutePath());

            $this->data = $data;
        }

        return $this->data;
    }

    /**
     * Obtain repository set with ordering and filters.
     *
     * @return \Softworx\RocXolid\Repositories\Contracts\Repository
     */
    protected function initRepository(): Repository
    {
        $repository = $this->getController()->getRepository();

        return $repository
            // ->setScopes($this->getScopes())
            ->setOrderBy($this->getOrderByColumn(), $this->getOrderByDirection())
            ->setFilters($this->getFilters());
    }

    /**
     * Get session key to store table state.
     *
     * @param string $aspect Table aspect eg. ordering state.
     * @return string
     */
    protected function getSessionKey(string $aspect): string
    {
        return sprintf('%s-%s-%s', md5(get_class($this)), $this->getParam(), $aspect);
    }

    // @todo better put this in some definition class
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

    // @todo component building options - better put this in a trait
    public function processTableOptions(): Table
    {
        $this->mergeOptions([
            'component' => [
                'id' => ViewHelper::domId($this, 'table')
            ]
        ]);

        return $this;
    }

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

    public function setCustomOptions($custom_options): Table
    {
        $this->mergeOptions($custom_options);

        return $this;
    }
}
