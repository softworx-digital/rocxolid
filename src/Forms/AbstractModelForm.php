<?php

namespace Softworx\RocXolid\Forms;

use Softworx\RocXolid\Contracts\Modellable;
use Softworx\RocXolid\Traits\Modellable as ModellableTrait;

abstract class AbstractModelForm extends AbstractForm implements Modellable
{
    use ModellableTrait;
}
