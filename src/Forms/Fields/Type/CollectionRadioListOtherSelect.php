<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Forms\Fields\AbstractFormField;
use Softworx\RocXolid\Models\AbstractCrudModel;

class CollectionRadioListOtherSelect extends AbstractFormField
{
    protected $show_null_option = false;

    protected $collection = null;

    protected $select_collection = null;

    protected $default_options = [
        'type-template' => 'collection-radio-list-other-select',
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
            $this->collection = $query->get()->transform(function (AbstractCrudModel $item) {
                return $item->initAsFieldItem($this);
            });
        }

        return $this;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function setSelectCollection($option)
    {
        if ($option instanceof Collection) {
            $this->select_collection = $option;
        } else {
            $model = ($option['model'] instanceof Model) ? $option['model'] : new $option['model'];
            $query = $model::query();

            if (isset($option['filters'])) {
                foreach ($option['filters'] as $filter) {
                    $query = (new $filter['class']())->apply($query, $model, $filter['data']);
                }
            }

            $this->select_collection = $query->pluck(sprintf('%s.%s', $model->getTable(), $option['column']), sprintf('%s.id', $model->getTable()));
        }

        return $this;
    }

    public function getSelectCollection()
    {
        if (empty($this->select_collection)) {
            return collect([]);
        }

        return $this->select_collection;
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

    public function isSelectFieldValue($value)
    {
        return $this->select_collection->has($value);
    }
}
