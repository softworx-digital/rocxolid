<?php

namespace Softworx\RocXolid\Rendering\Exceptions;

use Illuminate\Support\Collection;
// rocXolid exceptions
use Softworx\RocXolid\Exceptions\Exception;
// rocXolid rendering contracts
use Softworx\RocXolid\Rendering\Contracts\Renderable;

/**
 * Serves to be passed to a "not-found" view.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class ViewNotFoundException extends Exception
{
    /**
     * @var \Softworx\RocXolid\Rendering\Contracts\Renderable
     */
    protected $component;

    /**
     * @var string
     */
    protected $view_name;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $search_paths;

    /**
     * Constructor.
     *
     * @var \Softworx\RocXolid\Rendering\Contracts\Renderable $component Component being rendered.
     * @var string $view_name View name being retrieved path for.
     * @var \Illuminate\Support\Collection $search_paths Invalid paths the service was trying to find in the view.
     */
    public function __construct(Renderable $component, string $view_name, Collection $search_paths)
    {
        $this->component = $component;
        $this->view_name = $view_name;
        $this->search_paths = $search_paths;

        $this->message = sprintf("Cannot find view\n[%s]\nfor component\n[%s]\nsearched in folders:\n%s", $view_name, get_class($component), $search_paths->toJson());
    }

    /**
     * Component getter.
     *
     * @return \Softworx\RocXolid\Rendering\Contracts\Renderable
     */
    public function getComponent(): Renderable
    {
        return $this->component;
    }

    /**
     * View name getter.
     *
     * @return string
     */
    public function getViewName(): string
    {
        return $this->view_name;
    }

    /**
     * Search paths getter.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSearchPaths(): Collection
    {
        return $this->search_paths;
    }
}
