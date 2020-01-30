<?php

namespace Softworx\RocXolid\Models\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Contracts\Auth\Access\Authorizable;

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
     * @todo: subject to refactoring
     */
    public function apply(Builder $builder, Model $model)
    {
        $user = auth('rocXolid')->user();

        // nothing to apply not having a user instance
        if (!$user) {
            return;
        }

        // nothing to apply if user has policy.scope.all permissions
        if ($user->can('viewAny', [ get_class($model), get_class($model), 'policy.scope.all' ])) {
            return;
        }

        // scoping users first // @todo: kinda hotfixed, find some nicer approach
        if ($model instanceof $user) {
            if ($user->can('view', [ get_class($model), $model, null, 'policy.scope.all' ])) {
                return;
            }

            $this->handleUserScope($builder, $model, $user);
        // a true model-user ownership
        } elseif (($relation = $model->getOwnershipRelation()) && ($relation->getRelated() instanceof $user)) {
            // polymorph relations have to be resolved first since they are extending simple ones
            // MorphTo
            if ($relation instanceof MorphTo) {
                $this->handleOwnerMorphToScope($relation, $builder, $model, $user);
            // MorphToMany
            } elseif ($relation instanceof MorphToMany) {
                $this->handleOwnerMorphToManyScope($relation, $builder, $model, $user);
            // BelongsTo
            } elseif ($relation instanceof BelongsTo) {
                $this->handleOwnerBelongsToScope($relation, $builder, $model, $user);
            // MorphOneOrMany
            // } elseif ($relation instanceof MorphOneOrMany) {
                // $this->handleOwnerMorphOneOrManyScope($relation, $builder, $model, $user);
            // HasOneOrMany
            // } elseif ($relation instanceof HasOneOrMany) {
                // $this->handleOwnerHasOneOrManyScope($relation, $builder, $model, $user);
            // unsupported relation type
            } else {
                throw new \RuntimeException(sprintf(
                    'Unsupported relation type [%s] for [%s->%s()]',
                    get_class($relation),
                    get_class($model),
                    $relation->getRelationName(),
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
    private function handleOwnerMorphToScope(MorphTo $relation, Builder $builder, Model $model, Authorizable $user)
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
    private function handleOwnerMorphToManyScope(MorphToMany $relation, Builder $builder, Model $model, Authorizable $user)
    {
        return $builder
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
    private function handleOwnerBelongsToScope(BelongsTo $relation, Builder $builder, Model $model, Authorizable $user)
    {
        $builder->where($relation->getQualifiedForeignKeyName(), $user->getKey());
    }

    private function handleOwnerMorphOneOrManyScope(MorphOneOrMany $relation, Builder $builder, Model $model, Authorizable $user)
    {
        dd(__METHOD__, '@TDOO');
    }

    private function handleOwnerHasOneOrManyScope(HasOneOrMany $relation, Builder $builder, Model $model, Authorizable $user)
    {
        dd(__METHOD__, '@TDOO');
    }
}
