<?php

namespace Softworx\RocXolid\Components;

use Softworx\RocXolid\Contracts\Controllable;
use Softworx\RocXolid\Contracts\Modellable;
use Softworx\RocXolid\Contracts\Routable;
use Softworx\RocXolid\Traits\Controllable as ControllableTrait;
use Softworx\RocXolid\Traits\Modellable as ModellableTrait;
use Softworx\RocXolid\Traits\Routable as RoutableTrait;

abstract class AbstractActiveComponent extends AbstractComponent implements Controllable, Modellable, Routable
{
    use ControllableTrait;
    use ModellableTrait;
    use RoutableTrait;

    /*
     * @todo this doesn't quite work for related component models, eg. for images
     *
    protected function makeDomId(...$params): string
    {
        if ($this->hasModel() && $this->getModel()->exists) {
            return parent::makeDomId($this->getModel()->getKey(), ...$params);
        }

        return parent::makeDomId(...$params);
    }
    */
}
