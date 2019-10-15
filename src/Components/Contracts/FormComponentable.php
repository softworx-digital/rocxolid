<?php

namespace Softworx\RocXolid\Components\Contracts;

use Softworx\RocXolid\Components\Contracts\Formable;

interface FormComponentable
{
    public function setFormComponent(Formable $component): FormComponentable;

    public function getFormComponent(): Formable;
}
