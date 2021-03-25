<?php

namespace Softworx\RocXolid\Models\Contracts;

use Illuminate\Contracts\Auth\Access\Authorizable;

// @todo define
interface Crudable
{
    public static function getAuthorizationParameter(): ?string;

    public function isOwnership(Authorizable $user): bool;
}
