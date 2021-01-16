<?php

namespace Softworx\RocXolid\Models\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Contracts\Auth\Access\Authorizable;
// rocXolid model relations
use Softworx\RocXolid\Models\Relations as rxRelations;

/**
 * Scope to filter owned resources.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid\UserManagement
 * @version 1.0.0
 */
class Owned implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     * @todo subject to refactoring
     */
    public function apply(Builder $builder, Model $model)
    {
        $user = auth('rocXolid')->user();

        // nothing to apply not having a user instance
        if (!$user) {
            return;
        }

        // nothing to apply if user has policy.scope.all permissions
        if ($user->can('viewAnyAll', [ $model, $model ])) {
            return;
        }

        // scoping users first // @todo kinda "hotfixed", find some nicer approach
        if ($model instanceof $user) {
            $this->handleUserScope($builder, $model, $user);
        // a true model-user ownership
        } elseif (($relation = $model->getOwnershipRelation()) && ($relation->getRelated() instanceof $user)) {
            // polymorph relations have to be resolved first since they are extending simple ones
            // MorphTo
            if ($relation instanceof Relations\MorphTo) {
                $this->handleOwnerMorphToScope($relation, $builder, $model, $user);
            // MorphToMany
            } elseif ($relation instanceof Relations\MorphToMany) {
                $this->handleOwnerMorphToManyScope($relation, $builder, $model, $user);
            // BelongsTo
            } elseif ($relation instanceof Relations\BelongsTo) {
                $this->handleOwnerBelongsToScope($relation, $builder, $model, $user);
            // BelongsToThrough
            } elseif ($relation instanceof rxRelations\BelongsToThrough) {
                $this->handleOwnerBelongsToThroughScope($relation, $builder, $model, $user);
            // MorphOneOrMany
            // } elseif ($relation instanceof MorphOneOrMany) {
                // $this->handleOwnerMorphOneOrManyScope($relation, $builder, $model, $user);
            // HasOneOrMany
            // } elseif ($relation instanceof HasOneOrMany) {
                // $this->handleOwnerHasOneOrManyScope($relation, $builder, $model, $user);
            // unsupported relation type
            } else {
                throw new \RuntimeException(sprintf(
                    'Unsupported relation type [%s] for [%s::getOwnershipRelation()]',
                    get_class($relation),
                    get_class($model)
                ));
            }
        }
    }

    /**
     * Add scope for directly browsing users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Illuminate\Contracts\Auth\Access\Authorizable $user
     */
    private function handleUserScope(Builder $builder, Model $model, Authorizable $user)
    {
        $column = sprintf('%s.%s', $model->getTable(), $model->getKeyName());

        $builder->where($column, $user->getKey());
    }

    /**
     * Add scope for MorphTo ownership relation.
     *
     * @param \Illuminate\Database\Eloquent\Relations\MorphTo $relation
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Illuminate\Contracts\Auth\Access\Authorizable $user
     */
    private function handleOwnerMorphToScope(Relations\MorphTo $relation, Builder $builder, Model $model, Authorizable $user)
    {
        $builder
            ->where($relation->getMorphType(), $relation->getMorphedModel())
            ->where($relation->getQualifiedForeignKeyName(), $user->getKey());
    }

    /**
     * Add scope for MorphToMany ownership relation.
     *
     * @param \Illuminate\Database\Eloquent\Relations\MorphToMany $relation
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Illuminate\Contracts\Auth\Access\Authorizable $user
     */
    private function handleOwnerMorphToManyScope(Relations\MorphToMany $relation, Builder $builder, Model $model, Authorizable $user)
    {
        $builder
            ->join($relation->getTable(), $relation->getQualifiedForeignPivotKeyName(), '=', $relation->getQualifiedParentKeyName())
            ->where($relation->getMorphType(), $relation->getMorphClass())
            ->where($relation->getQualifiedRelatedPivotKeyName(), $user->getKey());
    }

    /**
     * Add scope for BelongsTo ownership relation.
     *
     * @param \Illuminate\Database\Eloquent\Relations\BelongsTo $relation
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Illuminate\Contracts\Auth\Access\Authorizable $user
     */
    private function handleOwnerBelongsToScope(Relations\BelongsTo $relation, Builder $builder, Model $model, Authorizable $user)
    {
        $builder->where($relation->getQualifiedForeignKeyName(), $user->getKey());
    }

    /**
     * Add scope for BelongsToThrough ownership relation.
     *
     * @param \Softworx\RocXolid\Models\Relations\BelongsToThrough $relation
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Illuminate\Contracts\Auth\Access\Authorizable $user
     */
    private function handleOwnerBelongsToThroughScope(rxRelations\BelongsToThrough $relation, Builder $builder, Model $model, Authorizable $user)
    {
        $relation
            ->performParentJoins($builder)
            ->where($relation->getQualifiedForeignKeyName(), $user->getKey());
    }

    private function handleOwnerMorphOneOrManyScope(Relations\MorphOneOrMany $relation, Builder $builder, Model $model, Authorizable $user)
    {
        dd(__METHOD__, '@TDOO');
    }

    private function handleOwnerHasOneOrManyScope(Relations\HasOneOrMany $relation, Builder $builder, Model $model, Authorizable $user)
    {
        dd(__METHOD__, '@TDOO');
    }
}
