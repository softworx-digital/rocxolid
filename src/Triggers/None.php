<?php

namespace Softworx\RocXolid\Triggers;

// rocXolid trigger contracts
use Softworx\RocXolid\Triggers\Contracts\Trigger;
// rocXolid triggers
use Softworx\RocXolid\Triggers\AbstractTrigger;

/**
 * No-action trigger.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class None extends AbstractTrigger
{
    public function fire(...$arguments): Trigger
    {
        return $this;
    }
}
