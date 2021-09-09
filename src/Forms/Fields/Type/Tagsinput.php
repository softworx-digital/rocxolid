<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Fields\Type\CollectionSelect;

class TagsInput extends CollectionSelect
{
    protected $default_options = [
        'type-template' => 'tagsinput',
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
            'class' => 'form-control nosubmit nopicker',
            'multiple' => 'multiple',
            'data-role' => 'tagsinput',
        ],
    ];

    public function getFieldName(int $index = 0): string
    {
        return sprintf('%s[]', parent::getFieldName($index));
    }

    protected function init()
    {
        $this->setCollection(collect($this->getForm()->getModel()->{$this->getName()})->flip());

        return $this;
    }
}
