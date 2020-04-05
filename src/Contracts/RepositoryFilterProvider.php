<?php

namespace Softworx\RocXolid\Contracts;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * Enables object to be used for repository filtering.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface RepositoryFilterProvider
{
    /**
     * Check if to apply the filter.
     *
     * @return boolean
     */
    public function isAppliable(): bool;

    /**
     * Apply the filter to the repository results.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query Query to apply filter to.
     * @return \Softworx\RocXolid\Repositories\Contracts\Repository
     */
    public function apply(EloquentBuilder $query): EloquentBuilder;
}
