<?php

namespace Softworx\RocXolid\Forms\Traits\Crud;

// rocXolid field types
use Softworx\RocXolid\Forms\Fields\Type\ButtonSubmitActions;
use Softworx\RocXolid\Forms\Fields\Type\ButtonGroup;

/**
 * Definition trait to add default buttons to a CRUD form.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait DefaultButtons
{
    /**
     * @var array|false
     */
    protected $buttons = [
        // submit - default group
        'submit' => [
            'type' => ButtonSubmitActions::class,
            'options' => [
                'group' => ButtonGroup::DEFAULT_NAME,
                'label' => [
                    'title' => 'submit-back',
                ],
                'actions' => [
                    'submit-edit' => 'submit-edit',
                    'submit-new' => 'submit-new',
                ],
                'attributes' => [
                    'class' => 'btn btn-success'
                ],
            ],
        ],/*
        'submit-ajax' => [
            'type' => ButtonSubmitActions::class,
            'options' => [
                'group' => ButtonGroup::DEFAULT_NAME,
                'ajax' => true,
                'label' => [
                    'title' => 'submit-back',
                ],
                'actions' => [
                    'submit-edit' => 'submit-edit',
                    'submit-new' => 'submit-new',
                ],
                'attributes' => [
                    'class' => 'btn btn-success',
                ],
            ],
        ],*/
    ];
}
