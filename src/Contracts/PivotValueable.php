<?php

namespace Softworx\RocXolid\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Enables object to have dynamic run-time pivot data assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface PivotValueable
{
    /**
     * Add pivot object to the container.
     *
     * @param \Illuminate\Database\Eloquent\Relations\Pivot $pivot Pivot to add.
     * @return \Softworx\RocXolid\Contracts\PivotValueable
     */
    public function addPivot(Pivot $pivot): PivotValueable;

    /**
     * Create and add pivot with given attributes to the container.
     *
     * @param \Illuminate\Database\Eloquent\Relations\BelongsToMany $relation Model relation reference.
     * @param array $attributes Pivot attributes.
     * @return \Softworx\RocXolid\Contracts\PivotValueable
     */
    public function addNewPivot(BelongsToMany $relation, array $attributes): PivotValueable;

    /**
     * Set pivot data collection.
     *
     * @param \Illuminate\Support\Collection $pivot_data Pivot data collection to set.
     * @return \Softworx\RocXolid\Contracts\PivotValueable
     */
    public function setPivotData(Collection $pivot_data): PivotValueable;

    /**
     * Check pivot data been set.
     *
     * @return bool
     */
    public function hasPivotData(): bool;

    /**
     * Get the pivot data collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPivotData(): Collection;

    /**
     * Check if model is present in pivot data collection.
     *
     * @param \Illuminate\Eloquent\Model $model Model to check pivot data presence.
     * @return bool
     * @throws \UnderflowException If pivot data not been set.
     */
    public function hasModelPivotData(Model $model): bool;

    /**
     * Get pivot data from collection for given model.
     *
     * @param \Illuminate\Eloquent\Model $model Model to get pivot data for.
     * @return \Illuminate\Database\Eloquent\Relations\Pivot
     * @throws \UnderflowException If model not present in pivot collection.
     */
    public function getModelPivotData(Model $model): ?Pivot;

    /**
     * Check if pivot attribute value matches given value.
     *
     * @param \Illuminate\Eloquent\Model $model Model to check pivot data value.
     * @param string $attribute Pivot attribute to check value of.
     * @param mixed $value Value to check.
     * @return bool
     */
    public function isModelPivotAttributeValue(Model $model, string $attribute, $value): bool;
}
