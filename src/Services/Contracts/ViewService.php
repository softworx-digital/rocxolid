<?php

namespace Softworx\RocXolid\Services\Contracts;

use Illuminate\View\View;
use Softworx\RocXolid\Contracts\Renderable;

/**
 * Retrieves view for given object and view name.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface ViewService
{
    /**
     * Searches for given view.
     *
     * @param Renderable $component Component to retrieve view for.
     * @param string $view View (file w/out 'blade.php'') name to retrieve.
     * @param array $assignments View variables to assign.
     * @return \Illuminate\View\View
     */
    public function getView(Renderable $component, $view_name, $assignments = []): View;

    /**
     * Gets full path for given view.
     *
     * @param Renderable $component Component to retrieve view for.
     * @param string $view View name.
     * @param string $directory_separator Path directory separator.
     * @return string
     */
    public function getViewPath(Renderable $component, $view_name, $directory_separator = '.'): string;
}
