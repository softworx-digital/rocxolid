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
}