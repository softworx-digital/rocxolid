<?php

namespace Softworx\RocXolid\Components\Navbar;

use Softworx\RocXolid\Contracts\Controllable;
use Softworx\RocXolid\Contracts\Routable;
use Softworx\RocXolid\Traits\Controllable as ControllableTrait;
use Softworx\RocXolid\Traits\Routable as RoutableTrait;

class ActiveItem extends Item implements Controllable, Routable
{
    use ControllableTrait;
    use RoutableTrait;

    public function isRouteActive(): bool
    {
        if (parent::isRouteActive()) {
            return true;
        }

        return get_class($this->getController()) === get_class(request()->route()->getController());
    }
}
