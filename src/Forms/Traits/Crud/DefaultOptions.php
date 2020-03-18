<?php

namespace Softworx\RocXolid\Forms\Traits\Crud;

/**
 * Definition trait to add default options to a CRUD form.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait DefaultOptions
{
    /**
     * @var array
     */
    protected $options = [
        'method' => 'POST',
        //'route-action' => '<action>',
        'class' => 'form-horizontal form-label-left',
    ];
}
