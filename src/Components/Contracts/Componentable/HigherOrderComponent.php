<?php

namespace Softworx\RocXolid\Components\Contracts\Componentable;

use Softworx\RocXolid\Components\AbstractComponent;

/**
 * Allow component to be wrapped into another component and used inside.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface HigherOrderComponent
{
    /**
     * Set wrapped component.
     *
     * @param \Softworx\RocXolid\Components\General\Alert $component
     * @return \Softworx\RocXolid\Components\Contracts\Componentable\Alert
     */
    public function setWrappedComponent(AbstractComponent $component): HigherOrderComponent;

    /**
     * Get wrapped component.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getWrappedComponent(): AbstractComponent;

    /**
     * Check if the wrapped component is assigned.
     *
     * @return bool
     */
    public function hasWrappedComponent(): bool;
}
