<?php

namespace Softworx\RocXolid\Forms;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
// contracts
use Softworx\RocXolid\Contracts\Controllable;
use Softworx\RocXolid\Contracts\Modellable;
use Softworx\RocXolid\Contracts\Repositoryable;
use Softworx\RocXolid\Forms\Contracts\Form;
use Softworx\RocXolid\Forms\Contracts\Formable as FormableContract;
// traits
use Softworx\RocXolid\Traits\Controllable as ControllableTrait;
use Softworx\RocXolid\Traits\Modellable as ModellableTrait;
use Softworx\RocXolid\Traits\Repositoryable as RepositoryableTrait;
// fields
use Softworx\RocXolid\Forms\Fields\Type\Input;
use Softworx\RocXolid\Forms\Fields\Type\Select;
use Softworx\RocXolid\Forms\Fields\Type\Switchery;
use Softworx\RocXolid\Forms\Fields\Type\Radio;
use Softworx\RocXolid\Forms\Fields\Type\Checkbox;
use Softworx\RocXolid\Forms\Fields\Type\FormFieldGroupAddable;
use Softworx\RocXolid\Forms\Fields\Type\ButtonSubmit;
use Softworx\RocXolid\Forms\Fields\Type\ButtonSubmitActions;
use Softworx\RocXolid\Forms\Fields\Type\ButtonAnchor;
use Softworx\RocXolid\Forms\Fields\Type\ButtonGroup;
use Softworx\RocXolid\Forms\Fields\Type\ButtonToolbar;
// utility
use Softworx\RocXolid\Http\Requests\CrudRequest;

/**
 *
 */
abstract class AbstractCrudForm extends AbstractForm implements Controllable, Modellable, Repositoryable
{
    use ControllableTrait;
    use ModellableTrait;
    use RepositoryableTrait;

    protected $options = [
        'method' => 'POST',
        //'route-action' => '<action>',
        'class' => 'form-horizontal form-label-left',
    ];

    protected $repository = null;

    protected $fieldgroups = false;

    protected $buttontoolbars = false;

    protected $buttongroups = [
        ButtonGroup::DEFAULT_NAME => [
            'type' => ButtonGroup::class,
            'options' => [
                'wrapper' => false,
                'attributes' => [
                    'class' => 'btn-group pull-right'
                ],
            ],
        ],
    ];

    protected $buttons = [
        // submit - default group
        'submit' => [
            'type' => ButtonSubmitActions::class,
            'options' => [
                'group' => ButtonGroup::DEFAULT_NAME,
                'label' => [
                    'title' => 'submit_back',
                ],
                'actions' => [
                    'submit-edit' => 'submit_edit',
                    'submit-new' => 'submit_new',
                ],
                'attributes' => [
                    'class' => 'btn btn-success'
                ],
            ],
        ],/*
        'submit-ajax' => [
            'type' => ButtonSubmitActions::class,
            'options' => [
                'group' => ButtonGroup::DEFAULT_NAME,
                'ajax' => true,
                'label' => [
                    'title' => 'submit_ajax_back',
                ],
                'actions' => [
                    'submit-edit' => 'submit_ajax_edit',
                    'submit-new' => 'submit_ajax_new',
                ],
                'attributes' => [
                    'class' => 'btn btn-success',
                ],
            ],
        ],*/
    ];

    public function buildFields($validate = true): Form
    {
        parent::buildFields($validate);

        if ($this->getModel()->exists && !$this->wasSubmitted()) {
            $this->setFieldsModelValues();
        }

        return $this;
    }

    public function setFieldsModelValues(): Form
    {
        $this->getModelAttributes()->each(function ($value, $attribute) {
            if ($this->hasFormField($attribute)) {
                $this
                    ->getFormField($attribute)
                        //->setValue($value, $index)
                        ->setValue($value)
                        ->updateParent();
            }
        });

        $this->getModelRelationships()->each(function ($relation, $attribute) {
            if ($this->hasFormField($attribute)) {
                $this
                    ->getFormField($attribute)
                        //->setValue($value, $index)
                        ->setValue($relation->pluck(sprintf('%s.id', $relation->getRelated()->getTable())))
                        ->updateParent();
            }
        });

        return $this;
    }

    public function setHolderProperties(FormableContract $repository): Form
    {
        $this->repository = $repository;

        $this
            ->setController($this->repository->getController());

        if ($this->repository->getController()->hasModel()) {
            $this
                ->setModel($this->repository->getController()->getModel());
        } else {
            throw new \RuntimeException(sprintf('No model set to [%s]', get_class($this->repository->getController())));
        }

        return $this;
    }

    public function makeRouteAction($route_action): string
    {
        if ($this->getModel()->exists) {
            return $this->repository->getController()->getRoute($route_action, $this->getModel());
        } else {
            return $this->repository->getController()->getRoute($route_action);
        }
    }

    public function adjustCreate(CrudRequest $request)
    {
        return $this;
    }

    public function adjustCreateBeforeSubmit(CrudRequest $request)
    {
        return $this;
    }

    public function adjustUpdate(CrudRequest $request)
    {
        return $this;
    }

    public function adjustUpdateBeforeSubmit(CrudRequest $request)
    {
        return $this;
    }

    // @todo - ked bude field builder upgradeovany cez factory (uz je, ale este nefactoruje objekty), tak rovno tvorba fieldov a nie len definicii
    // aj to asi nejako krajsie rozdelit - processovanie fieldov (definicii), lebo by sa este mali adjustovat
    protected function getFieldsDefinition(): array
    {
        if (!$this->fields) {
            $attributes = $this->getModelAttributes();

            if (!$attributes->count()) {
                throw new \InvalidArgumentException(sprintf('Model [%s] has no defined fillable attributes', get_class($this->getModel())));
            }

            foreach ($this->getModelAttributes() as $attribute => $value) {
                $column = $this->getConnection()->getDoctrineColumn($this->getModel()->getTable(), $attribute);

                $this->fields[$attribute] = $this->getFormFieldFactory()->makeFieldDefinition($column);
            }

            foreach ($this->getModelRelationships() as $attribute => $relation) {
                $this->fields[$attribute] = $this->getFormFieldFactory()->makeRelationFieldDefinition($this->getConnection(), $attribute, $relation);
            }
        }

        return $this->adjustFieldsDefinition($this->fields);
    }

    protected function getModelAttributes(): Collection
    {
        $attributes = new Collection();

        /*
        foreach ($this->getModel()->toArray() as $attribute => $value)
        {
            if (!in_array($attribute, $this->getModel()->getHidden()))
            {
                $attributes->put($attribute, $this->getModel()->exists ? $value : null);
            }
        }
        */

        foreach ($this->getModel()->getFillable() as $attribute) {
            if (!in_array($attribute, $this->getModel()->getHidden())) {
                $attributes->put($attribute, $this->getModel()->exists ? $this->getModel()->$attribute : null);
            }
        }

        foreach ($this->getModel()->getExtraAttributes() as $attribute) {
            $attributes->put($attribute, $this->getModel()->exists ? $this->getModel()->$attribute : null);
        }

        return $attributes;
    }

    protected function getModelRelationships(): Collection
    {
        $relationships = new Collection();

        foreach ($this->getModel()->getRelationshipMethods() as $method) {
            $relation = $this->getModel()->$method();
            $attribute = $this->getFormFieldFactory()->getRelationshipFieldName($method, $relation);

            $relationships->put($attribute, $relation);
        }

        return $relationships;
    }

    // @todo - toto zrejme niekam upratat
    // skusit do novej classy a nastavit nech sa ta injectuje miesto laravelovskej Illuminate\Database\Connection
    protected function getConnection()
    {
        $connection = DB::connection();
        $connection
            ->getDoctrineConnection()
            ->getDatabasePlatform()
            ->registerDoctrineTypeMapping('enum', 'string');

        return $connection;
    }
}
