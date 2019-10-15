<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class UploadFile extends AbstractFormField
{
    protected $default_options = [
        'type-template' => 'upload-file',
        // multiple
        'multiple' => false,
        // field wrapper
        'wrapper' => false,
        // component helper classes
        'helper-classes' => [
            'error-class' => 'has-error',
            'success-class' => 'has-success',
        ],
        // field label
        'label' => false,
        // upload url
        'upload-url' => null,
        // field HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
    ];

    public function setMultiple($multiple)
    {
        return $this->setComponentOptions('attributes', [ 'multiple' => $multiple ]);
    }

    public function setUploadUrl($url)
    {
        return $this->setComponentOptions('upload-url', $url);
    }
}
