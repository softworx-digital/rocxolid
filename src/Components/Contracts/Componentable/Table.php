<?php

namespace Softworx\RocXolid\Components\Contracts\Componentable;

use Softworx\RocXolid\Components\Contracts\Tableable;

/**
 * Allow tableable component to be added.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Table
{
    /**
     * Set tableable component.
     *
     * @param \Softworx\RocXolid\Components\Contracts\Tableable $component
     * @return \Softworx\RocXolid\Components\Contracts\Componentable\Table
     */
    public function setTableComponent(Tableable $component): Table;

    /**
     * Get tableable component.
     *
     * @return \Softworx\RocXolid\Components\Contracts\Tableable
     */
    public function getTableComponent(): Tableable;
}
