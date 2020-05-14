<?php

namespace Softworx\RocXolid\Forms\Builders\Contracts;

use Softworx\RocXolid\Forms\Contracts\Form;
use Softworx\RocXolid\Forms\Contracts\FormField;
use Softworx\RocXolid\Forms\Contracts\FormFieldable;

// @todo - dodefinovat
interface FormFieldFactory
{
    public function makeField(Form $form, FormFieldable $parent, $type, $name, array $options = []): FormField;
}
