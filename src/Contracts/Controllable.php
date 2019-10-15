<?php

namespace Softworx\RocXolid\Contracts;

use Illuminate\Routing\Controller;

/**
 * Enables object to have a controller assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Controllable
{
    /**
     * Set the controller.
     *
     * @param \Illuminate\Routing\Controller $controller Controller to be assigned.
     * @return \Softworx\RocXolid\Contracts\Controllable
     */
    public function setController(Controller $controller): Controllable;

    /**
     * Get the assigned controller.
     *
     * @return \Illuminate\Routing\Controller
     * @throws \UnderflowException If no controller is set.
     */
    public function getController(): Controller;

    /**
     * Check if the controller is assigned.
     *
     * @return bool
     */
    public function hasController(): bool;
}
