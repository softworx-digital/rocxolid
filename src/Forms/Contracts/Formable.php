<?php

namespace Softworx\RocXolid\Forms\Contracts;

use Softworx\RocXolid\Forms\Contracts\Form;
use Softworx\RocXolid\Components\Contracts\Formable as FormableComponent;

interface Formable
{
    const FORM_PARAM = 'general';

    public function createForm($class): Form;

    public function setForm(Form $form, $param = self::FORM_PARAM): Formable;

    public function getForms(): array;

    public function getForm($param = self::FORM_PARAM): Form;

    public function hasFormAssigned($param = self::FORM_PARAM): bool;

    public function hasFormClass($param = self::FORM_PARAM): bool;

    public function setFormComponent(FormableComponent $form_component, $param = self::FORM_PARAM): Formable;

    public function getFormComponent($param = self::FORM_PARAM): FormableComponent;
}
