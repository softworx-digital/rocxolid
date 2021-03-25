<?php

namespace Softworx\RocXolid\Rendering\Contracts;

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
    const DEFAULT_TEMPLATE_NAME = 'default';

    /**
     * Possibility to dynamically set properties before rendering.
     * Can be overriden in specific Renderable class.
     *
     * @return \Softworx\RocXolid\Rendering\Contracts\Renderable
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
     * Check if the cache should be used when rendering this component.
     *
     * @return bool
     */
    public function useRenderingCache(): bool;

    /**
     * Compose the blade template and returns it compiled as a string.
     *
     * @param string $view_name Name of the view to compose.
     * @param array $assignments Array of key-value params to assign to the view.
     * @return \Illuminate\View\View
     */
    public function fetch(string $view_name, array $assignments = []): string;

    /**
     * Set a view package to the component.
     *
     * @param string $package Package identifier to set.
     * @return \Softworx\RocXolid\Rendering\Contracts\Renderable
     */
    public function setViewPackage(string $package): Renderable;

    /**
     * Return view package set to the component.
     *
     * @return string
     */
    public function getViewPackage(): string;

    /**
     * Check if the component has a view package defined.
     *
     * @return bool
     */
    public function hasViewPackage(): bool;

    /**
     * Set a view directory of the view package to the component.
     *
     * @param string $directory Directory path to set.
     * @return \Softworx\RocXolid\Rendering\Contracts\Renderable
     */
    public function setViewDirectory(string $directory): Renderable;

    /**
     * Get a view directory of the view package of the component.
     *
     * @return string
     */
    public function getViewDirectory(): string;

    /**
     * Check if the component has a view directory defined.
     *
     * @return bool
     */
    public function hasViewDirectory(): bool;

    /**
     * Return name of the default template to be used when rendering unspecified template.
     *
     * @return string
     */
    public function getDefaultTemplateName(): string;

    /**
     * Create full view candidate path(s) based on given package, package subdirectory and view name.
     *
     * @param string $view_package View package to get the view from.
     * @param string $view_dir Directory inside the package.
     * @param string $view_name View name.
     * @return array
     */
    public function composePackageViewPaths(string $view_package, string $view_dir, string $view_name): array;

    /**
     * Generate key for caching the view paths.
     *
     * @param string $view_name View name.
     * @return string
     */
    public function getPackageViewCacheKey(string $view_name): string;
}
