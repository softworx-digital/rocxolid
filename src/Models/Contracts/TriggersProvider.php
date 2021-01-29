<?php

namespace Softworx\RocXolid\Models\Contracts;

use Illuminate\Support\Collection;

/**
 * Enables triggers to be provided to models.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface TriggersProvider
{
    /**
     * Obtain available triggers that can be assigned to the model.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAvailableTriggers(): Collection;

    /**
     * Retrieve triggers' definitions.
     *
     * @return \Illuminate\Support\Collection
     */
    public function provideTriggers(): Collection;

    /**
     * Check if the provider contains required trigger types.
     *
     * @param string $required_trigger_type
     * @return bool
     */
    public function containsTriggerTypes(Collection $required_trigger_types): bool;

    /**
     * Check if all assigned triggers are fireable.
     *
     * @param ...$arguments
     * @return bool
     */
    public function allTriggersFireable(...$arguments): bool;
}
