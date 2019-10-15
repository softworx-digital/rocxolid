<?php

namespace Softworx\RocXolid\Services;

use Softworx\RocXolid\Forms\FormBuilder;

// @todo - asi cez contracty
class FormService
{
    /**
     * Route reference.
     *
     * @var FormBuilder
     */
    protected $form_builder;

    /**
     * Contructor.
     *
     * @param FormBuilder $form_builder Form builder.
     * @return FormService
     */
    public function __construct(FormBuilder $form_builder)
    {
        $this->form_builder = $form_builder;
    }

    /**
     * Returns form.
     *
     * @return \Softworx\RocXolid\Forms\AbstractForm
     */
    public function getForm($action, $params = null)
    {
        dd(__METHOD__);
        return null;
        //return $this->form_builder;
    }
}
