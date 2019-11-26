<?php

namespace Softworx\RocXolid\Forms\Support;

use Illuminate\Support\Str;
// doctrine
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\Type;
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
// rocXolid field types
use Softworx\RocXolid\Forms\Fields\Type\BooleanRadio;
use Softworx\RocXolid\Forms\Fields\Type\Button;
use Softworx\RocXolid\Forms\Fields\Type\ButtonAnchor;
use Softworx\RocXolid\Forms\Fields\Type\ButtonGroup;
use Softworx\RocXolid\Forms\Fields\Type\ButtonSubmit;
use Softworx\RocXolid\Forms\Fields\Type\ButtonToolbar;
use Softworx\RocXolid\Forms\Fields\Type\Checkbox;
use Softworx\RocXolid\Forms\Fields\Type\CheckboxToggle;
use Softworx\RocXolid\Forms\Fields\Type\CollectionCheckbox;
use Softworx\RocXolid\Forms\Fields\Type\CollectionSelect;
use Softworx\RocXolid\Forms\Fields\Type\Colorpicker;
use Softworx\RocXolid\Forms\Fields\Type\Datepicker;
use Softworx\RocXolid\Forms\Fields\Type\Timepicker;
use Softworx\RocXolid\Forms\Fields\Type\DateTimepicker;
use Softworx\RocXolid\Forms\Fields\Type\FormFieldGroup;
use Softworx\RocXolid\Forms\Fields\Type\FormFieldGroupAddable;
use Softworx\RocXolid\Forms\Fields\Type\Input;
use Softworx\RocXolid\Forms\Fields\Type\Radio;
use Softworx\RocXolid\Forms\Fields\Type\Select;
use Softworx\RocXolid\Forms\Fields\Type\Switchery;
use Softworx\RocXolid\Forms\Fields\Type\Textarea;
use Softworx\RocXolid\Forms\Fields\Type\WysiwygTextarea;
// rocXolid contracts
use Softworx\RocXolid\Contracts\EventDispatchable;
use Softworx\RocXolid\Forms\Contracts\Form;
use Softworx\RocXolid\Forms\Contracts\FormField;
use Softworx\RocXolid\Forms\Contracts\FormFieldable;
use Softworx\RocXolid\Forms\Contracts\FormFieldFactory as FormFieldFactoryContract;

/**
 *
 */
class FormFieldFactory implements FormFieldFactoryContract
{
    /**
     * Mappings for DB column types.
     *
     * @var array
     */
    protected static $fields_mapping = [
        Type::SMALLINT      => Input::class,
        Type::INTEGER       => Input::class,
        Type::BIGINT        => Input::class,
        Type::DECIMAL       => Input::class,
        Type::FLOAT         => Input::class,
        Type::STRING        => Input::class,
        Type::TEXT          => Textarea::class,
        Type::GUID          => Input::class,
        Type::BINARY        => Input::class,
        Type::BLOB          => Input::class,
        Type::BOOLEAN       => CheckboxToggle::class,
        Type::DATE          => Datepicker::class,
        Type::DATETIME      => DateTimepicker::class,
        Type::DATETIMETZ    => DateTimepicker::class,
        Type::TIME          => Timepicker::class,
        Type::TARRAY        => Input::class,
        Type::SIMPLE_ARRAY  => Input::class,
        Type::JSON_ARRAY    => Input::class,
        Type::OBJECT        => Input::class,
    ];
    /**
     * Special mappings for certain column names.
     *
     * @var array
     */
    protected static $fields_name_mapping = [
        'color'             => Colorpicker::class,
        'text_color'        => Colorpicker::class,
    ];

    // @todo - zrejme inak - nie cez generovanie definicii, ale priamu tvorbu fieldov
    public function makeFieldDefinition(Column $column, $rules = []): array
    {
        $type = $this->getFieldTypeClass($column);

        if ($column->getNotnull()) {
            $rules[] = 'required';
        }

        if ($column->getLength()) {
            $rules[] = 'max:' . $column->getLength();
        }

        switch ($column->getType()->getName()) {
            case Type::SMALLINT:
            case Type::INTEGER:
            case Type::BIGINT:
                $rules[] = 'integer';
                $rules[] = sprintf('min:%s', $column->getUnsigned() ? 0 : -1 * pow(10, $column->getPrecision() - $column->getScale() - 1));
                $rules[] = sprintf('max:%s', pow(10, $column->getPrecision() - $column->getScale() - 1));
                break;
            case Type::DECIMAL:
            case Type::FLOAT:
                $rules[] = 'numeric';
                $rules[] = sprintf('min:%s', $column->getUnsigned() ? 0 : -1 * pow(10, $column->getPrecision() - $column->getScale() - 1));
                $rules[] = sprintf('max:%s', pow(10, $column->getPrecision() - $column->getScale() - 1));
                break;
            case Type::BOOLEAN:
                $rules[] = 'in:0,1';
                break;
            case Type::DATE:
            case Type::DATETIME:
            case Type::DATETIMETZ:
            case Type::TIME:
                $rules[] = 'date';
                break;
        }

        return [
            'type' => $type,
            'options' => [
                'label' => [
                    'title' => $column->getName(),
                ],
                'placeholder' => [
                    'title' => $column->getDefault(),
                ],
                'validation' => [
                    'rules' => $rules,
                ],
            ],
        ];
    }
    // @todo - refactorovat a dat na instanceof v style MorphMany je HasOneOrMany
    // + typy vstupov
    public function makeRelationFieldDefinition($doctrine_connection, $attribute, Relation $relation, $rules = []): array
    {
        if ($relation instanceof HasOne) {
            $related = $relation->getRelated();

            return [
                'type' => CollectionSelect::class,
                'options' => [
                    'label' => [
                        'title' => $attribute,
                    ],
                    'collection' => [
                        'model' => $related,
                        'column' => $related->getTitleColumn(),
                    ],
                    'validation' => [
                        'rules' => $rules,
                    ],
                ],
            ];
        }

        if ($relation instanceof HasMany) {
            $related = $relation->getRelated();

            return [
                'type' => CollectionCheckbox::class,
                'options' => [
                    'label' => [
                        'title' => $attribute,
                    ],
                    'collection' => [
                        'model' => $related,
                        'column' => $related->getTitleColumn(),
                    ],
                    'validation' => [
                        'rules' => $rules,
                    ],
                ],
            ];
        }

        if ($relation instanceof HasOneOrMany) {
            $related = $relation->getRelated();

            return [
                'type' => CollectionCheckbox::class,
                'options' => [
                    'label' => [
                        'title' => $attribute,
                    ],
                    'collection' => [
                        'model' => $related,
                        'column' => $related->getTitleColumn(),
                    ],
                    'validation' => [
                        'rules' => $rules,
                    ],
                ],
            ];
        }

        if ($relation instanceof BelongsTo) {
            $related = $relation->getRelated();

            $column = $doctrine_connection->getDoctrineColumn($relation->getParent()->getTable(), $attribute);

            return [
                'type' => CollectionSelect::class,
                'options' => [
                    'label' => [
                        'title' => $attribute,
                    ],
                    'collection' => [
                        'model' => $related,
                        'column' => $related->getTitleColumn(),
                    ],
                    'validation' => [
                        'rules' => $rules,
                    ],
                    'attributes' => [
                        'placeholder' => !$column->getNotNull() ? 'select' : false,
                    ],
                ],
            ];
        }

        if ($relation instanceof BelongsToMany) {
            $related = $relation->getRelated();

            return [
                'type' => CollectionCheckbox::class,
                'options' => [
                    'label' => [
                        'title' => $attribute,
                    ],
                    'collection' => [
                        'model' => $related,
                        'column' => $related->getTitleColumn(),
                    ],
                    'validation' => [
                        'rules' => $rules,
                    ],
                ],
            ];
        }

        return [];
    }

    public function getRelationshipFieldName($method, $relation): string
    {
        if ($relation instanceof BelongsTo) {
            return sprintf('%s_id', Str::snake($method));
        }

        return $method;
    }

    public function makeField(Form $form, FormFieldable $parent, $type, $name, array $options = []): FormField
    {
        $field = new $type($name, $type, $form, $parent, $options);

        return $field;
    }

    public function makeRelationField(Relation $relation, $rules = []): FormField
    {
        if ($relation instanceof MorphToMany) {
            $related = $relation->getRelated();

            $field = new CollectionCheckbox();
            $field->setCollection($related->pluck('name', 'id'));

            $field->setOptions([
                'label' => [
                    'title' => $relation->getName()
                ],
                'validation' => [
                    'rules' => $rules,
                ],
            ]);
        }

        return $field;
    }

    protected function getFieldTypeClass(Column $column)
    {
        if (!array_key_exists($column->getType()->getName(), self::$fields_mapping)) {
            throw new \InvalidArgumentException(sprintf('Undefined field type for column [%s] type [%s]', $column->getName(), $column->getType()->getName()));
        }

        if (array_key_exists($column->getName(), self::$fields_name_mapping)) {
            $type = self::$fields_name_mapping[$column->getName()];
        } else {
            $type = self::$fields_mapping[$column->getType()->getName()];
        }

        return $type;
    }
}
