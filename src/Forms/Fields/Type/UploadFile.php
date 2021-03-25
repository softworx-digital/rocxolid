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
            'class' => 'form-control',
            // 'maxsize' => '5242880', // 5 MB
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

    /**
     * {@inheritDoc}
     */
    public function getFieldName(int $index = 0): string
    {
        if ($this->isArray()) {
            return sprintf('%s[%s][%s]', self::ARRAY_DATA_PARAM, $index, $this->name);
        } else {
            return sprintf('%s[%s]', self::SINGLE_DATA_PARAM, $this->name);
        }
    }
}
