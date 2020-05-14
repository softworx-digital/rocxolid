<?php

namespace Softworx\RocXolid\Http\Controllers\Traits\Components;

// rocXolid forms
use Softworx\RocXolid\Forms\Contracts\Form;
// rocXolid components
use Softworx\RocXolid\Components\Forms\CrudForm as CrudFormComponent;

/**
 * Helper trait to obtain form component.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait FormComponentable
{
    protected static $form_component_type = CrudFormComponent::class;

    /**
     * Retrieve form component to show.
     *
     * @param \Softworx\RocXolid\Forms\Contracts\Form $form
     * @return \Softworx\RocXolid\Components\Forms\CrudForm
     */
    public function getFormComponent(Form $form): CrudFormComponent
    {
        return static::$form_component_type::build($this, $this)
            ->setForm($form)
            ->setRepository($this->getRepository());
    }
}
