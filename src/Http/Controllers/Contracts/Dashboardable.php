<?php

namespace Softworx\RocXolid\Http\Controllers\Contracts;

// rocXolid components
use Softworx\RocXolid\Components\AbstractActiveComponent;

/**
 * Enables controller to have a dashboard component assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Dashboardable
{
    /**
     * Retrieve assigned dashboard class.
     * 
     * @return string
     */
    public function getDashboardClass(): string;
}
