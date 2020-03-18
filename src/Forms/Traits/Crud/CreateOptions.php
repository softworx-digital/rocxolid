<?php

namespace Softworx\RocXolid\Forms\Traits\Crud;

/**
 * Definition trait to add create model options to a CRUD form.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait CreateOptions
{
    /**
     * @var array
     */
    protected $options = [
        'method' => 'POST',
        'route-action' => 'store',
        'class' => 'form-horizontal form-label-left',
    ];
}
