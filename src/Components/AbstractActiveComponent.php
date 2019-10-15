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

    protected function getTranslationKey($key, $use_repository_param = true)
    {
        if (!$use_repository_param) {
            return sprintf('general.%s', $key);
        } elseif ($this->hasController() && $this->getController()->getRepository()) {
            return sprintf('%s.%s', $this->getController()->getRepository()->getTranslationParam(), $key);
        } else {//if ($this->getController() && $this->getController()->getRepository())
            return '---component--- (' . $key . ' -- ' . __METHOD__ . ')';
        }

        return $key;
    }
}
