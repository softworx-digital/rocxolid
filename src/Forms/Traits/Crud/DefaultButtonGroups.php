<?php

namespace Softworx\RocXolid\Forms\Traits\Crud;

// rocXolid field types
use Softworx\RocXolid\Forms\Fields\Type\ButtonGroup;

/**
 * Definition trait to add default button groups to a CRUD form.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait DefaultButtonGroups
{
    /**
     * @var array|false
     */
    protected $buttongroups = [
        ButtonGroup::DEFAULT_NAME => [
            'type' => ButtonGroup::class,
            'options' => [
                'wrapper' => false,
                'attributes' => [
                    'class' => 'btn-group pull-right'
                ],
            ],
        ],
    ];
}
