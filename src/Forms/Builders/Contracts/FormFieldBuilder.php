<?php

namespace Softworx\RocXolid\Forms\Builders\Contracts;

use Softworx\RocXolid\Forms\Contracts\Form;

// @todo: finish definition
interface FormFieldBuilder
{
    public function addDefinitionFields(Form $form, array $definition, ?array $form_fields_order_definition): FormFieldBuilder;

    public function addDefinitionButtons(Form $form, array $definition, ?array $form_buttons_order_definition): FormFieldBuilder;
}
