<?php

namespace Softworx\RocXolid\Components;

use Softworx\RocXolid\Components\Contracts\Renderable;
use Softworx\RocXolid\Traits\Renderable as RenderableTrait;

/**
 * Intermediate class, because there's a conflict with properties when AbstractComponent uses Renderable trait.
 *
 */
abstract class AbstractRenderableComponent implements Renderable
{
    const DEFAULT_TEMPLATE_NAME = 'default';

    use RenderableTrait;

    public function setPreRenderProperties(...$elements)
    {
        return $this;
    }
}
