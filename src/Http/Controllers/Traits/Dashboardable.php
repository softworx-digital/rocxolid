<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

// rocXolid components
use Softworx\RocXolid\Components\AbstractActiveComponent;

/**
 * Enables controller to have a dashboard component assigned.
 */
trait Dashboardable
{
    // protected static $dashboard_type; // should be defined in specific class

    /**
     * @var Softworx\RocXolid\Components\AbstractActiveComponent
     */
    protected $dashboard;

    /**
     * {@inheritdoc}
     */
    public function getDashboardClass(): string
    {
        return static::$dashboard_type;
    }

    /**
     * Create and retrieve dashboard object.
     *
     * @return \Softworx\RocXolid\Components\AbstractActiveComponent
     */
    protected function getDashboard(): AbstractActiveComponent
    {
        if (is_null($this->dashboard)) {
            $class = $this->getDashboardClass();

            $this->dashboard = $class::build();
        }

        return $this->dashboard;
    }
}
