<?php

namespace Softworx\RocXolid\Components\Contracts;

use Softworx\RocXolid\Forms\Contracts\Form;

interface Formable
{
    public function setForm(Form $form): Formable;

    public function getForm(): Form;

    public function getFormFieldsComponents();

    public function getFormButtonsComponents();
}
