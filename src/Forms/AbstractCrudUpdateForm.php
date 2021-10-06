<?php

namespace Softworx\RocXolid\Forms;

// rocXolid forms
use Softworx\RocXolid\Forms\AbstractCrudForm;

/**
 * @todo subject to refactoring
 */
abstract class AbstractCrudUpdateForm extends AbstractCrudForm
{
    /**
     * @inheritDoc
     */
    protected $options = [
        'method' => 'POST',
        'route-action' => 'update',
        'class' => 'form-horizontal form-label-left',
    ];
}
