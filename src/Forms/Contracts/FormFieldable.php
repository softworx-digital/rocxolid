<?php

namespace Softworx\RocXolid\Forms\Contracts;

use Illuminate\Support\Collection;
use Softworx\RocXolid\Forms\Contracts\FormField;

// @todo - dodefinovat, ak treba
interface FormFieldable
{
    public function addFormField(FormField $form_field): FormFieldable;

    public function getFormFields(): Collection;

    public function hasFormField($field): bool;

    public function getFormField($field): FormField;
}
