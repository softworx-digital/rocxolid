<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Forms\Contracts\FormField;
use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class CollectionRadioList extends AbstractFormField
{
    protected $show_null_option = false;

    protected $collection = null;

    protected $default_options = [
        'type-template' => 'collection-radio-list',
        // field wrapper
        'wrapper' => false,
        // component helper classes
        'helper-classes' => [
            'error-class' => 'has-error',
            'success-class' => 'has-success',
        ],
        // field label
        'label' => false,
        'except-attributes' => null,
        // field HTML attributes
        'attributes' => [
            'class' => 'form-control',
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

            $this->collection = $query->get();
        }

        return $this;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function setExceptAttributes($attributes)
    {
        $this->setComponentOptions('except-attributes', $attributes);

        return $this;
    }

    public function getFieldName(int $index = 0): string
    {
        if ($this->isArray()) {
            return sprintf('%s[%s][%s]', self::ARRAY_DATA_PARAM, $index, $this->name);
        } else {
            return sprintf('%s[%s]', self::SINGLE_DATA_PARAM, $this->name);
        }
    }
}
