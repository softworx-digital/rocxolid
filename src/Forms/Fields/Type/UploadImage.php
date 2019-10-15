<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Contracts\FormField;
use Softworx\RocXolid\Forms\Fields\AbstractFormField;
use Softworx\RocXolid\Forms\Fields\Type\UploadFile;

class UploadImage extends UploadFile
{
    protected $default_options = [
        'type-template' => 'upload-image',
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
        // image preview size
        'image-preview-size' => 'small',
        // field HTML attributes
        'attributes' => [
            'class' => 'form-control'
        ],
    ];

    protected function setImagePreviewSize($preview_size): FormField
    {
        return $this->setComponentOptions('image-preview-size', $preview_size);
    }
}
