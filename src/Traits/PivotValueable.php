<?php

namespace Softworx\RocXolid\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// rocXolid contracts
use Softworx\RocXolid\Contracts\PivotValueable as PivotValueableContract;

/**
 * Enables object to have dynamic run-time pivot data assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait PivotValueable
{
    /**
     * @var \Illuminate\Support\Collection $pivot_data Pivot values container.
     */
    private $pivot_data;

    /**
     * @var string $pivot_relation_name Pivot relation name.
     */
    private $pivot_relation_name;

    /**
     * {@inheritdoc}
     */
    public function addPivot(Pivot $pivot): PivotValueableContract
    {
        $this->pivot_data->put($pivot->{$pivot->getRelatedKey()}, $pivot);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addNewPivot(BelongsToMany $relation, array $attributes): PivotValueableContract
    {
        $this->pivot_data = collect($this->pivot_data);

        $this->addPivot($relation->newPivot($attributes));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPivotData(Collection $pivot_data): PivotValueableContract
    {
        $this->pivot_data = collect();

        $pivot_data->each(function ($pivot) {
            $this->addPivot($pivot);
        });

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPivotData(): bool
    {
        return isset($this->pivot_data);
    }

    /**
     * {@inheritdoc}
     */
    public function getPivotData(): Collection
    {
        if (!$this->hasPivotData()) {
            throw new \UnderflowException(sprintf('No pivot data set in [%s]', get_class($this)));
        }

        return $this->pivot_data;
    }

    /**
     * {@inheritdoc}
     */
    public function hasModelPivotData(Model $model): bool
    {
        if (!$this->hasPivotData()) {
            throw new \UnderflowException(sprintf('No model [%s] pivot data set in [%s]', get_class($model), get_class($this)));
        }

        return $this->pivot_data->where($model->getForeignKey(), $model->getKey())->isNotEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function getModelPivotData(Model $model): ?Pivot
    {
        if (!$this->hasModelPivotData($model)) {
            // throw new \UnderflowException(sprintf('No pivot data set in [%s] for model [%s:%s]', get_class($this), get_class($model), $model->getKey()));
            // return Pivot::make();
        }

        return $this->pivot_data->where($model->getForeignKey(), $model->getKey())->first();
    }

    /**
     * {@inheritdoc}
     */
    public function isModelPivotAttributeValue(Model $model, string $attribute, $value): bool
    {
        return $this->getModelPivotData($model) && ($this->getModelPivotData($model)->$attribute === $value);
    }

    /**
     * Set relation name for pivot field.
     *
     * @param string $pivot_relation_name
     * @return \Softworx\RocXolid\Traits\PivotValueableContract
     */
    protected function setPivotFor(string $pivot_relation_name): PivotValueableContract
    {
        $this->pivot_relation_name = $pivot_relation_name;

        return $this;
    }

    public function isPivotFor(BelongsToMany $relation): string
    {
        return $this->pivot_relation_name === $relation->getRelationName();
    }
}
