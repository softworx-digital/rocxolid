<?php

namespace Softworx\RocXolid\Traits;

use App;
use Illuminate\View\View;
use Softworx\RocXolid\Services\Contracts\ViewService;
use Softworx\RocXolid\Contracts\Renderable as RenderableContract;

/**
 * Enables object to be rendered at the front-end.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Renderable
{
    /**
     * @var string $view_package Identifier for package containing views for component using this trait.
     */
    // protected $view_package; // should be defined in package specific (Abstract)Component class

    /**
     * @var string $view_directory Enables to define specific directory containing views for component using this trait.
     */
    // protected $view_directory; // should be defined in package specific (Abstract)Component class

    /**
     * {@inheritdoc}
     * @todo find some cleaner way
     */
    public function setPreRenderProperties(...$elements): RenderableContract
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     * @todo find some way to handle exceptions not during content rendering, but first try to render and then throw exceptions on error
     */
    public function render(string $view_name = null, array $assignments = []): View
    {
        $view_name = is_null($view_name)
                   ? $this->getDefaultTemplateName()
                   : $view_name;

        return $this->getViewService()->getView($this, $view_name, $assignments + [
            'component' => $this
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(string $view_name = null, array $assignments = []): string
    {
        return (string)$this->render($view_name, $assignments)->render();
    }

    /**
     * {@inheritdoc}
     */
    public function setViewPackage(string $package): RenderableContract
    {
        $this->view_package = $package;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getViewPackage(): string
    {
        return $this->view_package;
    }

    /**
     * {@inheritdoc}
     */
    public function hasViewPackage(): bool
    {
        return !is_null($this->view_package);
    }

    /**
     * {@inheritdoc}
     */
    public function setViewDirectory(string $directory): RenderableContract
    {
        $this->view_directory = $directory;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getViewDirectory(): string
    {
        return $this->view_directory;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultTemplateName(): string
    {
        return static::DEFAULT_TEMPLATE_NAME;
    }

    /**
     * Retrieves the view service responsible for retrieving and composing the views.
     * @todo: pass as dependency via class constructor (however to all classes using this trait - awkward)
     *
     * @return \Softworx\RocXolid\Services\Contracts\ViewService
     * @todo Optimize adding view_service property?
     */
    protected function getViewService(): ViewService
    {
        if (!property_exists($this, 'view_service') || is_null($this->view_service)) {
            $view_service = App::make(ViewService::class);

            if (property_exists($this, 'view_service')) {
                $this->view_service = $view_service;
            }
        } elseif (property_exists($this, 'view_service')) {
            $view_service = $this->view_service;
        }

        return $view_service;
    }
}
