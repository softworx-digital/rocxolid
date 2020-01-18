<?php

namespace Softworx\RocXolid\Forms;

use Softworx\RocXolid\Contracts\Controllable;
use Softworx\RocXolid\Traits\Controllable as ControllableTrait;

abstract class AbstractControllerForm extends AbstractForm implements Controllable
{
    use ControllableTrait;
}
