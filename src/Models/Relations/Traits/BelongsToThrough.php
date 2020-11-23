<?php

namespace Softworx\RocXolid\Models\Relations\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
// rocXolid model relations
use Softworx\RocXolid\Models\Relations\BelongsToThrough as BelongsToThroughRelation;

trait BelongsToThrough
{
    /**
     * Define a belongs-to-through relationship.
     *
     * @param string $related
     * @param array|string $through
     * @param string|null $local_key
     * @param string $prefix
     * @param array $foreign_key_lookup
     * @return \Softworx\RocXolid\Relations\BelongsToThrough
     */
    public function belongsToThrough(string $related, $through, array $foreign_key_lookup = [], $local_key = null, $prefix = ''): BelongsToThroughRelation
    {
        $related_instance = $this->newRelatedInstance($related);
        $through_parents = collect();
        $foreign_keys = collect();

        foreach ((array)$through as $model) {
            $foreign_key = null;

            if (is_array($model)) {
                $foreign_key = $model[1];

                $model = $model[0];
            }

            $instance = $this->belongsToThroughParentInstance($model);

            if ($foreign_key) {
                $foreign_keys->put($instance->getTable(), $foreign_key);
            }

            $through_parents->push($instance);
        }

        foreach ($foreign_key_lookup as $model => $foreign_key) {
            $instance = new $model;

            if ($foreign_key) {
                $foreign_keys->put($instance->getTable(), $foreign_key);
            }
        }

        return $this->newBelongsToThrough($related_instance->newQuery(), $this, $through_parents, $foreign_keys, $local_key, $prefix);
    }

    /**
     * Create a through parent instance for a belongs-to-through relationship.
     *
     * @param string $model
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function belongsToThroughParentInstance(string $model): Model
    {
        $segments = preg_split('/\s+as\s+/i', $model);

        /** @var \Illuminate\Database\Eloquent\Model $instance */
        $instance = new $segments[0];

        if (isset($segments[1])) {
            $instance->setTable(sprintf('%s AS %s', $instance->getTable(), $segments[1]));
        }

        return $instance;
    }

    /**
     * Instantiate a new BelongsToThrough relationship.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $parent
     * @param \Illuminate\Support\Collection $through_parents
     * @param \Illuminate\Support\Collection $foreign_key_lookup
     * @param string $local_key
     * @param string $prefix
     * @return \Softworx\RocXolid\Models\Relations\BelongsToThrough
     */
    protected function newBelongsToThrough(
        Builder $query,
        Model $parent,
        Collection $through_parents,
        Collection $foreign_key_lookup,
        ?string $local_key,
        ?string $prefix
    ): BelongsToThroughRelation {
        return new BelongsToThroughRelation($query, $parent, $through_parents, $foreign_key_lookup, $local_key, $prefix);
    }
}
