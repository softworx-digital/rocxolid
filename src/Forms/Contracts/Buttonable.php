<?php

namespace Softworx\RocXolid\Forms\Contracts;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Forms\Contracts\FormField;

interface Buttonable
{
    public function addButton(FormField $form_field): Buttonable;

    public function getButtons(): Collection;

    public function getButton($name): FormField;
}
