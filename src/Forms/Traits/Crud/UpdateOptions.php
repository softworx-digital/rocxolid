<?php

namespace Softworx\RocXolid\Forms\Traits\Crud;

// rocXolid field types
use Softworx\RocXolid\Forms\Fields\Type\ButtonGroup;

/**
 * Definition trait to add default options to a CRUD form.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait UpdateOptions
{
    /**
     * @var array
     */
    protected $options = [
        'method' => 'POST',
        'route-action' => 'update',
        'class' => 'form-horizontal form-label-left',
    ];
}
