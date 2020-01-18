<?php

namespace Softworx\RocXolid\Components\Contracts\Componentable;

use Softworx\RocXolid\Components\Contracts\Repositoryable;

/**
 * Allow repositoryable component to be added.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Repository
{
    /**
     * Set repositoryable component.
     *
     * @param \Softworx\RocXolid\Components\Contracts\Repositoryable $component
     * @return \Softworx\RocXolid\Components\Contracts\Componentable\Repository
     */
    public function setRepositoryComponent(Repositoryable $component): Repository;

    /**
     * Get repositoryable component.
     *
     * @return \Softworx\RocXolid\Components\Contracts\Repositoryable
     */
    public function getRepositoryComponent(): Repositoryable;
}
