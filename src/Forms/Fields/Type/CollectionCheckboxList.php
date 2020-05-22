<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Illuminate\Support\Collection;
// rocXolid form field types
use Softworx\RocXolid\Forms\Fields\Type\CollectionCheckbox;

class CollectionCheckboxList extends CollectionCheckbox
{
    protected $default_options = [
        'type-template' => 'collection-checkbox-list',
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
}
