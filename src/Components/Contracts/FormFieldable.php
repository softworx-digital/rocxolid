<?php

namespace Softworx\RocXolid\Components\Contracts;

use Softworx\RocXolid\Forms\Contracts\FormField;

interface FormFieldable
{
    public function setFormField(FormField $form_field): FormFieldable;

    public function getFormField();
}
