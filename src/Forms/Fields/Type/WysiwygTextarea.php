<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

class WysiwygTextarea extends Textarea
{
    protected $default_options = [
        'type-template' => 'textarea-wysiwyg',
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
            'class' => 'form-control wysiwyg'
        ],
    ];
    /**
     *{@inheritdoc}
     *//*
    protected function getTemplate()
    {
        return 'textarea-wysiwyg';
    }*/
}
