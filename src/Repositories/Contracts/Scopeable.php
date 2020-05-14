<?php

namespace Softworx\RocXolid\Repositories\Contracts;

use Illuminate\Database\Eloquent\Scope;
// rocXolid repository contracts
use Softworx\RocXolid\Repositories\Contracts\Repository;

interface Scopeable
{
    /**
     * Add scope to be applied to the query.
     *
     * @param \Illuminate\Database\Eloquent\Scope $scope
     * @return \Softworx\RocXolid\Repositories\Contracts\Repository
     */
    public function withScope(Scope $scope): Repository;

    /**
     * Remove scope from the query.
     *
     * @param \Illuminate\Database\Eloquent\Scope $scope
     * @return \Softworx\RocXolid\Repositories\Contracts\Repository
     */
    public function withoutScope(Scope $scope): Repository;
}
