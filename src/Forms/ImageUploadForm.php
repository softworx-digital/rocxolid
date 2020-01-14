<?php

namespace Softworx\RocXolid\Forms;

use Softworx\RocXolid\Forms\AbstractCrudForm as RocXolidAbstractCrudForm;
// fields
use Softworx\RocXolid\Forms\Fields\Type\UploadImage;

class ImageUploadForm extends RocXolidAbstractCrudForm
{
    protected $options = [
        'method' => 'POST',
        'route-action' => 'update',
        'class' => 'form-horizontal form-label-left',
        'section' => 'image',
    ];

    protected $buttons = [];

    protected function adjustFieldsDefinition($fields)
    {
        $fields = [];
        $fields['image']['type'] = UploadImage::class;
        $fields['image']['options']['multiple'] = false;

        return $fields;
    }
}
