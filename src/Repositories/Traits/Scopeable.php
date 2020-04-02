<?php

namespace Softworx\RocXolid\Repositories\Traits;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Scope;
// rocXolid repository contracts
use Softworx\RocXolid\Repositories\Contracts\Repository;
// rocXolid model scopes
use Softworx\RocXolid\Models\Scopes\Owned as OwnedScope;

/**
 * Trait to enable scope application to the query.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Scopeable
{
    /**
     * Scopes to be applied to the query.
     *
     * @var array
     */
    protected $with_scopes = [
        OwnedScope::class,
    ];

    /**
     * Scopes to be removed from the query.
     *
     * @var array
     */
    protected $without_scopes = [];

    /**
     * {@inheritDoc}
     */
    public function withScope(Scope $scope): Repository
    {
        $this->with_scopes[] = $scope;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withoutScope(Scope $scope): Repository
    {
        $this->without_scopes[] = $scope;

        return $this;
    }

    /**
     * Apply pre-set scopes to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Softworx\RocXolid\Repositories\Contracts\Repository
     */
    protected function applyScopes(EloquentBuilder &$query): Repository
    {
        collect($this->with_scopes)->each(function ($scope) {
            $query = $query->withGlobalScope($scope, app($scope));
        });

        $query = $query->withoutGlobalScopes($this->without_scopes);

        return $this;
    }
}
