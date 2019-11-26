<?php

namespace Softworx\RocXolid\Forms\Contracts;

// @todo: finish definition
interface FormFieldBuilder
{
    public function addDefinitionFields(Form $form, $definition, $form_fields_order_definition): FormFieldBuilder;

    public function addDefinitionButtons(Form $form, $definition, $form_buttons_order_definition): FormFieldBuilder;
}
