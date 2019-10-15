<?php

namespace Softworx\RocXolid\Components\Contracts;

use Softworx\RocXolid\Forms\Contracts\FormField;

interface FormButtonable
{
    public function setButton(FormField $form_field): FormButtonable;

    public function getButton();
}
