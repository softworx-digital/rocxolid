<?php

namespace Softworx\RocXolid\Forms\Fields\Type;

use Softworx\RocXolid\Forms\Fields\AbstractFormField;

class Colorpicker extends AbstractFormField
{
    protected $default_options = [
        'type-template' => 'colorpicker',
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
            //'class' => 'form-control colorpicker'
            'class' => 'form-control palette-colorpicker',
            'data-palette' => '["#CCC","#333","#6A6AFF","#75B4FF","#75D6FF","#24E0FB","#1FFEF3","#03F3AB","#0AFE47","#BF00BF","#BC2EBC","#A827FE","#9B4EE9","#6755E3","#2F74D0","#2897B7","#27DE55","#6CA870","#79FC4E","#32DF00","#61F200","#C8C800","#CDD11B","#FFF","#000"]'
        ],
    ];
}
