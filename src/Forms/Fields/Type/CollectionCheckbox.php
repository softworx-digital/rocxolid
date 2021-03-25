<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Illuminate\Support\Collection;
// rocXolid contracts
use Softworx\RocXolid\Contracts\Valueable;
// rocXolid form fields
use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class CollectionCheckbox extends AbstractFormField
{
    protected $collection = null;

    protected $default_options = [
        'type-template' => 'collection-checkbox',
        // field wrapper
        'wrapper' => false,
        // component helper classes
        'helper-classes' => [
            'error-class' => 'has-error',
            'success-class' => 'has-success',
        ],
        // field label
        'label' => [
            'collection' => [
                'attributes' => [
                    'class' => 'label-fit-height col-xl-3 col-lg-4 col-sm-6 col-xs-12'
                ]
            ],
        ],
        // field HTML attributes
        'attributes' => [
            'class' => 'flat'
        ],
        'except-attributes' => null,
        'enable-custom-values' => false,
    ];

    public function setCollection($option)
    {
        if ($option instanceof Collection) {
            $this->collection = $option;
        } elseif (isset($option['model']) && isset($option['column'])) {
            $model = ($option['model'] instanceof Model) ? $option['model'] : new $option['model'];
            $query = $model::query();

            if (isset($option['filters'])) {
                foreach ($option['filters'] as $filter) {
                    $query = (new $filter['class']())->apply($query, $model, $filter['data']);
                }
            }
            // @todo use qualify
            $this->collection = $query->pluck(sprintf('%s.%s', $model->getTable(), $option['column']), sprintf('%s.id', $model->getTable()));

            if (isset($option['method'])) {
                $method = $option['method'];

                $this->collection = $this->collection->map(function (&$item, $id) use ($model, $method) {
                    return $model->find($id)->{$method}();
                });
            }
        } else {
            throw new \InvalidArgumentException(sprintf('The "collection" option for [%s] field requires en entire collection to be set or model and column definition', $this->name));
        }

        return $this;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function getCustomValues($index = 0): Collection
    {
        return collect($this->getFieldValue($index))->filter(function ($value) {
            return filled($value) && !$this->getCollection()->keys()->contains($value);
        });
    }

    public function setValue($value, int $index = 0): Valueable
    {
        // coming from submitted data
        if (is_array($value)) {
            $value = collect($value);
        }

        return parent::setValue($value, $index);
    }

    public function isFieldValue($value, $index = 0): bool
    {
        return $this->getFieldValue($index) && $this->getFieldValue($index)->transform(function ($value) {
            return (string)$value;
        })->containsStrict((string)$value);
    }

    public function setExceptAttributes($attributes)
    {
        $this->setComponentOptions('except-attributes', $attributes);

        return $this;
    }

    public function setEnableCustomValues(bool $enable)
    {
        $this->setComponentOptions('enable-custom-values', $enable);

        return $this;
    }

    public function getFieldName(int $index = 0): string
    {
        if ($this->isArray()) {
            return sprintf('%s[%s][%s][]', self::ARRAY_DATA_PARAM, $index, $this->name);
        } else {
            return sprintf('%s[%s][]', self::SINGLE_DATA_PARAM, $this->name);
        }
    }
}
