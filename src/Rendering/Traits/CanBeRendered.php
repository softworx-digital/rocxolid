<?php

namespace Softworx\RocXolid\Rendering\Traits;

use Illuminate\View\View;
use Illuminate\Support\Facades\View as ViewFacade;
// rocXolid rendering contracts
use Softworx\RocXolid\Rendering\Contracts\Renderable;

/**
 * Enables object to be rendered at the front-end.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait CanBeRendered
{
    /**
     * Identifier for package containing views for component using this trait.
     *
     * @var string
     */
    protected $view_package;

    /**
     * Enables to define specific directory containing views for component using this trait.
     *
     * @var string
     */
    protected $view_directory;

    /**
     * {@inheritdoc}
     */
    public function setPreRenderProperties(...$elements): Renderable
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getViewPath(string $view_name = null): string
    {
        $view_name = is_null($view_name)
                   ? $this->getDefaultTemplateName()
                   : $view_name;

        return $this->getRenderingService()->getViewPath($this, $view_name);
    }

    /**
     * {@inheritdoc}
     */
    public function render(string $view_name = null, array $assignments = []): View
    {
        $view_name = is_null($view_name)
                   ? $this->getDefaultTemplateName()
                   : $view_name;

        $view = $this->getRenderingService()->getView($this, $view_name, $assignments + [
            'component' => $this
        ]);

        $this->renderViewActions($view);

        return $view;
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
    public function setViewPackage(string $package): Renderable
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
    public function setViewDirectory(string $directory): Renderable
    {
        $this->view_directory = $directory;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getViewDirectory(): string
    {
        return $this->view_directory ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function hasViewDirectory(): bool
    {
        return !is_null($this->view_directory);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultTemplateName(): string
    {
        return static::DEFAULT_TEMPLATE_NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function composePackageViewPaths(string $view_package, string $view_dir, string $view_name): array
    {
        return [ sprintf('%s::%s.%s', $view_package, $view_dir, $view_name) ];
    }

    /**
     * {@inheritDoc}
     */
    public function getPackageViewCacheKey(string $view_name): string
    {
        return sprintf('%s-%s-%s', get_class($this), $this->getViewPackage(), $view_name);
    }

    /**
     * Possibility to take some actions or adjust the view before rendering.
     *
     * @param \Illuminate\View\View $view
     * @return \Softworx\RocXolid\Rendering\Contracts\Renderable
     */
    protected function renderViewActions(View &$view): Renderable
    {
        return $this;
    }
}
