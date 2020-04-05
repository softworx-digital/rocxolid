<?php

namespace Softworx\RocXolid\Repositories\Contracts;

use Illuminate\Support\Collection;

/**
 * Enables filtering to repository query.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Filterable
{
    /**
     * Undocumented function
     *
     * @param \Illuminate\Support\Collection $filters
     * @return \Softworx\RocXolid\Repositories\Contracts\Filterable
     */
    public function setFilters(Collection $filters): Filterable;
}
