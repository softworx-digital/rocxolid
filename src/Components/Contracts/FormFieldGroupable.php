<?php

namespace Softworx\RocXolid\Components\Contracts;

use Softworx\RocXolid\Forms\Contracts\FormField;

interface FormFieldGroupable
{
    public function setFormFieldGroup(FormField $form_field_group): FormFieldGroupable;

    public function getFormFieldGroup();
}
