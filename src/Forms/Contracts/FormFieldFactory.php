<?php

namespace Softworx\RocXolid\Forms\Contracts;

// @todo - dodefinovat
interface FormFieldFactory
{
    public function makeField(Form $form, FormFieldable $parent, $type, $name, array $options = []): FormField;
}
