<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Illuminate\Support\Collection;
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
    ];

    public function setCollection($option)
    {
        if ($option instanceof Collection) {
            $this->collection = $option;
        } else {
            $model = ($option['model'] instanceof Model) ? $option['model'] : new $option['model'];
            $query = $model::query();

            if (isset($option['filters'])) {
                foreach ($option['filters'] as $filter) {
                    $query = (new $filter['class']())->apply($query, $model, $filter['data']);
                }
            }

            $this->collection = $query->pluck(sprintf('%s.%s', $model->getTable(), $option['column']), sprintf('%s.id', $model->getTable()));

            if (isset($option['method'])) {
                $method = $option['method'];

                $this->collection = $this->collection->map(function (&$item, $id) use ($model, $method) {
                    return $model->find($id)->{$method}();
                });
            }
        }

        return $this;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function getFieldName($index = 0)
    {
        if ($this->isArray()) {
            return sprintf('%s[%s][%s][]', self::ARRAY_DATA_PARAM, $index, $this->name);
        } else {
            return sprintf('%s[%s][]', self::SINGLE_DATA_PARAM, $this->name);
        }
    }

    public function isFieldValue($value, $index = 0)
    {
        if (!$this->getFieldValue($index) instanceof Collection) {
            $this->setValue(new Collection($this->getFieldValue($index)));
        }

        return  $this->getFieldValue($index)->contains($value);
    }
}
