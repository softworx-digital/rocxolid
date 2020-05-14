<?php

namespace Softworx\RocXolid\Components\Contracts\Componentable;

use Illuminate\Support\Collection;
// rocXolid components
use Softworx\RocXolid\Components\General\Alert as AlertComponent;

/**
 * Allow alert component to be added.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Alert
{
    /**
     * Add alert component.
     *
     * @param \Softworx\RocXolid\Components\General\Alert $component
     * @return \Softworx\RocXolid\Components\Contracts\Componentable\Alert
     */
    public function addAlertComponent(AlertComponent $component): Alert;

    /**
     * Get alert components.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAlertComponents(): Collection;
}
