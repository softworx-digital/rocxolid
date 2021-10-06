<?php

namespace Softworx\RocXolid\Forms\Builders;

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
use Softworx\RocXolid\Forms\Fields\Type as FieldType;
// rocXolid contracts
use Softworx\RocXolid\Contracts\EventDispatchable;
use Softworx\RocXolid\Forms\Contracts\Form;
use Softworx\RocXolid\Forms\Contracts\FormField;
use Softworx\RocXolid\Forms\Contracts\FormFieldable;
use Softworx\RocXolid\Forms\Builders\Contracts\FormFieldFactory as FormFieldFactoryContract;

/**
 * @todo complete refactoring needed
 */
class FormFieldFactory implements FormFieldFactoryContract
{
    /**
     * Mappings for DB column types.
     *
     * @var array
     */
    protected static $fields_mapping = [
        Type::SMALLINT      => FieldType\Input::class,
        Type::INTEGER       => FieldType\Input::class,
        Type::BIGINT        => FieldType\Input::class,
        Type::DECIMAL       => FieldType\Input::class,
        Type::FLOAT         => FieldType\Input::class,
        Type::STRING        => FieldType\Input::class,
        Type::TEXT          => FieldType\Textarea::class,
        Type::GUID          => FieldType\Input::class,
        Type::BINARY        => FieldType\Input::class,
        Type::BLOB          => FieldType\Input::class,
        Type::BOOLEAN       => FieldType\CheckboxToggle::class,
        Type::DATE          => FieldType\Datepicker::class,
        Type::DATETIME      => FieldType\DateTimepicker::class,
        Type::DATETIMETZ    => FieldType\DateTimepicker::class,
        Type::TIME          => FieldType\Timepicker::class,
        Type::TARRAY        => FieldType\Input::class,
        Type::SIMPLE_ARRAY  => FieldType\Input::class,
        Type::JSON_ARRAY    => FieldType\Input::class,
        Type::JSON          => FieldType\Input::class,
        Type::OBJECT        => FieldType\Input::class,
    ];
    /**
     * Special mappings for certain column names.
     *
     * @var array
     */
    protected static $fields_name_mapping = [
        'color'             => FieldType\Colorpicker::class,
        'text_color'        => FieldType\Colorpicker::class,
        'background_color'  => FieldType\Colorpicker::class,
        'subtitle_color'    => FieldType\Colorpicker::class,
    ];

    // @todo zrejme inak - nie cez generovanie definicii, ale priamu tvorbu fieldov
    public function makeFieldDefinition(Column $column, $rules = []): array
    {
        $type = $this->getFieldTypeClass($column);

        if ($column->getNotnull()) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
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
                $rules[] = 'date';
                break;
            case Type::TIME:
                $rules[] = 'regex:/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/';
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
    // @todo refactorovat a dat na instanceof v style MorphMany je HasOneOrMany
    // + typy vstupov
    public function makeRelationFieldDefinition($doctrine_connection, $attribute, Relation $relation, $rules = []): array
    {
        if ($relation instanceof HasOne) {
            $related = $relation->getRelated();

            return [
                'type' => FieldType\CollectionSelect::class,
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
                'type' => FieldType\CollectionCheckbox::class,
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
                'type' => FieldType\CollectionCheckbox::class,
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

            $column = $doctrine_connection->getDoctrineColumn($relation->getParent()->getTable(), $relation->getForeignKeyName());

            return [
                'type' => FieldType\CollectionSelect::class,
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
                'type' => FieldType\CollectionCheckbox::class,
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

            $field = app(FieldType\CollectionCheckbox::class);
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
