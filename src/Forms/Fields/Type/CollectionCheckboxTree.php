<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Illuminate\Support\Collection;
// rocXolid form field types
use Softworx\RocXolid\Forms\Fields\Type\CollectionCheckbox;
use Softworx\RocXolid\Models\AbstractCrudModel;

class CollectionCheckboxTree extends CollectionCheckbox
{
    protected $default_options = [
        'type-template' => 'collection-checkbox-tree',
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
            $model = ($option['model'] instanceof AbstractCrudModel) ? $option['model'] : new $option['model'];
            $query = $model::query();

            if (isset($option['filters'])) {
                foreach ($option['filters'] as $filter) {
                    $query = (new $filter['class']())->apply($query, $model, $filter['data']);
                }
            }

            // @todo ->select($this->queried_model->qualifyColumn('*'))
            $this->collection = $query
                ->get()->transform(function (AbstractCrudModel $item) {
                return $item->initAsFieldItem($this);
            });
        }

        return $this;
    }
}
