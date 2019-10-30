<?php

namespace Softworx\RocXolid\Contracts;

use Illuminate\Contracts\Events\Dispatcher;

/**
 * Enables to have event dispatcher assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid\Admin
 * @version 1.0.0
 */
interface EventDispatchable
{
    /**
     * Assign event dispatcher.
     * 
     * @param \Illuminate\Contracts\Events\Dispatcher $event_dispatcher
     */
    public function setEventDispatcher(Dispatcher $event_dispatcher);

    /**
     * Retrieve event dispatcher.
     * 
     * @return \Illuminate\Contracts\Events\Dispatcher
     * @throws \UnderflowException If no dispatcher is set.
     */
    public function getEventDispatcher(): Dispatcher;

    /**
     * Check if the event dispatcher is assigned.
     * 
     * @return bool
     */
    public function hasEventDispatcher(): bool;
}
