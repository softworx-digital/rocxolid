<?php

namespace Softworx\RocXolid\Rendering\Services\Contracts;

use Illuminate\View\View;
// rocXolid contracts
use Softworx\RocXolid\Rendering\Contracts\Renderable;

/**
 * Retrieves view for given object and view name.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface RenderingService
{
    /**
     * Indirectly render given view with given component  and assignments.
     * Used in Blade @render extension.
     *
     * @param \Softworx\RocXolid\Rendering\Contracts\Renderable $component
     * @param string $view_name
     * @param array $assignments
     * @return void
     */
    public static function renderComponent(Renderable $component, string $view_name = null, array $assignments = []);

    /**
     * Compile given content as a blade template.
     *
     * @param string $content Blade template content.
     * @param array $data Template data.
     * @return string
     */
    public static function render(string $content, array $data = []): string;

    /**
     * Return view for given component.
     *
     * @param \Softworx\RocXolid\Rendering\Contracts\Renderable $component Component to retrieve view for.
     * @param string $view View name to retrieve.
     * @param array $assignments View variables to assign.
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function getView(Renderable $component, string $view_name, array $assignments = []): View;

    /**
     * Get full view path for given view.
     *
     * @param \Softworx\RocXolid\Rendering\Contracts\Renderable $component Component to retrieve view path for.
     * @param string $view View name to get path for.
     * @return string
     */
    public function getViewPath(Renderable $component, string $view_name): string;
}
