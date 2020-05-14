<?php

namespace Softworx\RocXolid\Models\Relations;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\SupportsDefaultModels;
use Illuminate\Database\Eloquent\Relations\Relation;

class BelongsToThrough extends Relation
{
    use SupportsDefaultModels;

    /**
     * The column alias for the local key on the first "through" parent model.
     *
     * @var string
     */
    const THROUGH_KEY = 'laravel_through_key';

    /**
     * The "through" parent model instances.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $through_parents;

    /**
     * The foreign key prefix for the first "through" parent model.
     *
     * @var string
     */
    protected $prefix;

    /**
     * The custom foreign keys on the relationship.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $foreign_key_lookup;

    /**
     * Create a new belongs to through relationship instance.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $parent
     * @param \Illuminate\Database\Eloquent\Model[] $through_parents
     * @param string|null $local_key
     * @param string $prefix
     * @param array $foreign_key_lookup
     * @return void
     */
    public function __construct(Builder $query, Model $parent, Collection $through_parents, Collection $foreign_key_lookup, string $local_key = null, string $prefix = '')
    {
        $this->through_parents = $through_parents;
        $this->prefix = $prefix;
        $this->foreign_ley_lookup = $foreign_key_lookup;

        parent::__construct($query, $parent);
    }

    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    public function addConstraints()
    {
        $this->performJoins();

        if (static::$constraints) {
            $local_value = $this->parent->{$this->getFirstForeignKeyName()};

            $this->query->where($this->getQualifiedFirstLocalKeyName(), '=', $local_value);
        }
    }

    /**
     * Set the join clauses on the query (of related model).
     *
     * @return void
     */
    protected function performJoins()
    {
        $query = $this->query;

        $predecessor = $this->related;

        $this->through_parents->each(function ($model) use ($query, &$predecessor) {
            $first = $predecessor->getQualifiedKeyName();
            $joined = $model->qualifyColumn($this->getForeignKeyName($predecessor));

            $query->join($model->getTable(), $first, '=', $joined);

            if ($this->hasSoftDeletes($model)) {
                $this->query->whereNull($model->getQualifiedDeletedAtColumn());
            }

            $predecessor = $model;
        });
    }

    /**
     * Set the join clauses on the query (of parent model).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function performParentJoins(Builder $query): Builder
    {
        $predecessor = $this->parent;

        $this->through_parents->each(function ($model) use ($query, &$predecessor) {
            $first = $model->getQualifiedKeyName();
            $joined = $predecessor->qualifyColumn($this->getForeignKeyName($model));

            $query->join($model->getTable(), $first, '=', $joined);

            if ($this->hasSoftDeletes($model)) {
                $this->query->whereNull($model->getQualifiedDeletedAtColumn());
            }

            $predecessor = $model;
        });

        $query->join($this->related->getTable(), $this->related->getQualifiedKeyName(), '=', $predecessor->qualifyColumn($this->getForeignKeyName($this->related)));

        return $query;
    }

    /**
     * Get the foreign key for a model.
     *
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @return string
     */
    public function getForeignKeyName(Model $model = null): string
    {
        $table = explode(' AS ', ($model ?? $this->parent)->getTable())[0];

        return $this->foreign_ley_lookup->get($table, sprintf('%s_id', Str::singular($table)));
    }

    /**
     * Determine whether a model uses SoftDeletes.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function hasSoftDeletes(Model $model): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive($model));
    }

    /**
     * Set the constraints for an eager load of the relation.
     *
     * @param array $models
     * @return void
     */
    public function addEagerConstraints(array $models)
    {
        $keys = $this->getKeys($models, $this->getFirstForeignKeyName());

        $this->query->whereIn($this->getQualifiedFirstLocalKeyName(), $keys);
    }

    /**
     * Initialize the relation on a set of models.
     *
     * @param array $models
     * @param string $relation
     * @return array
     */
    public function initRelation(array $models, $relation): array
    {
        foreach ($models as $model) {
            $model->setRelation($relation, $this->getDefaultFor($model));
        }

        return $models;
    }

    /**
     * Match the eagerly loaded results to their parents.
     *
     * @param array $models
     * @param \Illuminate\Database\Eloquent\Collection $results
     * @param string $relation
     * @return array
     */
    public function match(array $models, EloquentCollection $results, $relation): array
    {
        $dictionary = $this->buildDictionary($results);

        foreach ($models as $model) {
            $key = $model[$this->getFirstForeignKeyName()];

            if (isset($dictionary[$key])) {
                $model->setRelation($relation, $dictionary[$key]);
            }
        }

        return $models;
    }

    /**
     * Build model dictionary keyed by the relation's foreign key.
     *
     * @param \Illuminate\Database\Eloquent\Collection $results
     * @return array
     */
    protected function buildDictionary(Collection $results): array
    {
        $dictionary = [];

        foreach ($results as $result) {
            $dictionary[$result[static::THROUGH_KEY]] = $result;

            unset($result[static::THROUGH_KEY]);
        }

        return $dictionary;
    }

    /**
     * Get the results of the relationship.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getResults(): Model
    {
        return $this->first() ?: $this->getDefaultFor($this->parent);
    }

    /**
     * Execute the query and get the first result.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function first(array $columns = ['*']): ?Model
    {
        if ($columns === ['*']) {
            $columns = [ sprintf('%s.*', $this->related->getTable()) ];
        }

        return $this->query->first($columns);
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get($columns = ['*']): EloquentCollection
    {
        $columns = $this->query->getQuery()->columns ? [] : $columns;

        if ($columns === ['*']) {
            $columns = [ sprintf('%s.*', $this->related->getTable()) ];
        }

        $columns[] = sprintf('%s AS %s', $this->getQualifiedFirstLocalKeyName(), static::THROUGH_KEY);

        $this->query->addSelect($columns);

        return $this->query->get();
    }

    /**
     * Add the constraints for a relationship query.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $parent
     * @param array|mixed $columns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getRelationExistenceQuery(Builder $query, Builder $parent, $columns = ['*']): Builder
    {
        $this->performJoins($query);

        $foreign_key = sprintf('%s.%s', $parent->getQuery()->from, $this->getFirstForeignKeyName());

        return $query->select($columns)->whereColumn($this->getQualifiedFirstLocalKeyName(), '=', $foreign_key);
    }

    /**
     * Restore soft-deleted models.
     *
     * @param array|string ...$columns
     * @return $this
     */
    public function withTrashed(...$columns): BelongsToThrough
    {
        if (empty($columns)) {
            $this->query->withTrashed();

            return $this;
        }

        if (is_array($columns[0])) {
            $columns = $columns[0];
        }

        $this->query->getQuery()->wheres = collect($this->query->getQuery()->wheres)
            ->reject(function ($where) use ($columns) {
                return $where['type'] === 'Null' && in_array($where['column'], $columns);
            })->values()->all();

        return $this;
    }

    /**
     * Get the foreign key for the first "through" parent model.
     *
     * @return string
     */
    public function getFirstForeignKeyName(): string
    {
        return sprintf('%s%s', $this->prefix, $this->getForeignKeyName($this->through_parents->last()));
    }

    /**
     * Get the qualified local key for the first "through" parent model.
     *
     * @return string
     */
    public function getQualifiedFirstLocalKeyName(): string
    {
        return $this->through_parents->last()->getQualifiedKeyName();
    }

    /**
     * Get the fully qualified foreign key of the relationship.
     *
     * @return string
     */
    public function getQualifiedForeignKeyName(): string
    {
        return $this->related->getQualifiedKeyName();
    }

    /**
     * Make a new related instance for the given model.
     *
     * @param \Illuminate\Database\Eloquent\Model $parent
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function newRelatedInstanceFor(Model $parent): Model
    {
        return $this->related->newInstance();
    }
}