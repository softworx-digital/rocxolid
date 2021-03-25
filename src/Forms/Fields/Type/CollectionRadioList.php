<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Forms\Fields\AbstractFormField;
use Softworx\RocXolid\Models\AbstractCrudModel;

class CollectionRadioList extends AbstractFormField
{
    protected $show_null_option = false;

    protected $collection = null;

    protected $default_options = [
        'type-template' => 'collection-radio-list',
        'collection-item-template' => 'include.labeled',
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
        'justified' => true,
        'enable-custom-values' => false,
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

    public function setCollectionItemTemplate($option)
    {
        $this->setComponentOptions('collection-item-template', $option);
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

    public function setExceptAttributes($attributes)
    {
        $this->setComponentOptions('except-attributes', $attributes);

        return $this;
    }

    public function setJustified(bool $justified)
    {
        $this->setComponentOptions('justified', $justified);

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
            return sprintf('%s[%s][%s]', self::ARRAY_DATA_PARAM, $index, $this->name);
        } else {
            return sprintf('%s[%s]', self::SINGLE_DATA_PARAM, $this->name);
        }
    }
}
