<?php

namespace Softworx\RocXolid\Contracts;

use Illuminate\View\View;

/**
 * Enables object to be rendered at the front-end.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Renderable
{
    /**
     * Possibility to dynamically set properties before rendering. 
     * Can be overriden in specific Renderable class.
     * 
     * @return \Softworx\RocXolid\Components\Contracts\Renderable
     */
    public function setPreRenderProperties(...$elements): Renderable;

    /**
     * Compose the blade template and returns it.
     *
     * @param string $view_name Name of the view to compose.
     * @param array $assignments Array of key-value params to assign to the view.
     * @return \Illuminate\View\View
     */
    public function render(string $view_name, array $assignments = []): View;

    /**
     * Compose the blade template and returns it compiled as a string.
     *
     * @param string $view_name Name of the view to compose.
     * @param array $assignments Array of key-value params to assign to the view.
     * @return \Illuminate\View\View
     */
    public function fetch(string $view_name, array $assignments = []): string;

    /**
     * Check if the component has a view package defined.
     *
     * @return bool
     */
    public function hasViewPackage(): bool;

    /**
     * Set a view package to the component.
     *
     * @param string $package Package identifier to set.
     * @return \Softworx\RocXolid\Components\Contracts\Renderable
     */
    public function setViewPackage(string $package): Renderable;

    /**
     * Return view packages set to the component.
     *
     * @return array
     */
    public function getViewPackages(): array;

    /**
     * Set a view directory of the view package to the component.
     *
     * @param string $directory Directory path to set.
     * @return \Softworx\RocXolid\Components\Contracts\Renderable
     */
    public function setViewDirectory(string $directory): Renderable;

    /**
     * Get a view directory of the view package of the component.
     *
     * @return string
     */
    public function getViewDirectory(): string;
}
