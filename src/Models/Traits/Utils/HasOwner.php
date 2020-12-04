<?php

namespace Softworx\RocXolid\Models\Traits\Utils;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Relations\Relation;
// rocXolid model scopes
use Softworx\RocXolid\Models\Scopes\Owned as OwnedScope;

/**
 * Defines ownership relation (eg. for scopes).
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait HasOwner
{
    /**
     * Boot this trait when trait's user boots.
     *
     * @return void
     */
    public static function bootHasOwner()
    {
        // @todo: commented out until better solution found, causes several problems
        // troublesome in selects, fills,...
        // static::addGlobalScope(app(OwnedScope::class));
    }

    /**
     * Check if given authorizable owns resource.
     *
     * @param \Illuminate\Contracts\Auth\Access\Authorizable $user Resource owner candidate.
     * @return bool
     */
    public function isOwnership(Authorizable $user): bool
    {
        $owner = $this->getOwnershipRelation()->get();

        if ($owner instanceof Collection) {
            return $owner->contains($user);
        }

        return $owner->is($user);
    }

    /**
     * Get model's ownership relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function getOwnershipRelation(): Relation
    {
        return $this->createdBy();
    }
}
