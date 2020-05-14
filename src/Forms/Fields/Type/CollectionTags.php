<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Contracts\FormField;
use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class CollectionTags extends AbstractFormField
{
    protected $show_null_option = false;

    protected $collection = null;

    protected $default_options = [
        'type-template' => 'collection-tags',
        // field wrapper
        'wrapper' => false,
        // component helper classes
        'helper-classes' => [
            'error-class' => 'has-error',
            'success-class' => 'has-success',
        ],
        // field label
        'label' => false,
        // field HTML attributes
        'attributes' => [
            'placeholder' => null,
            'class' => 'form-control',
            'data-role' => 'tagsinput',
        ],
    ];

    public function setCollection($option)
    {
        if ($option instanceof Collection) {
            $this->collection = $option;
        } else {
            $query = $model = ($option['model'] instanceof Model) ? $option['model'] : new $option['model'];

            if (isset($option['filters'])) {
                foreach ($option['filters'] as $filter) {
                    $query = (new $filter['class']())->apply($query, $model, $filter['data']);
                }
            }

            $this->collection = $query->pluck(sprintf('%s.%s', $model->getTable(), $option['column']), sprintf('%s.id', $model->getTable()));
        }

        return $this;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function getFieldName(int $index = 0): string
    {
        if ($this->isArray()) {
            return sprintf('%s[%s][%s]', self::ARRAY_DATA_PARAM, $index, $this->name);
        } else {
            return sprintf('%s[%s]', self::SINGLE_DATA_PARAM, $this->name);
        }
    }

    /*
        public function isFieldValue($value, $index = 0): bool
        {
            if (!$this->getFieldValue($index) instanceof Collection)
            {
                $this->setValue(collect($this->getFieldValue($index)));
            }

            return  $this->getFieldValue($index)->contains($value);
        }
    */
}
