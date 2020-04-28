<?php

namespace Softworx\RocXolid\Rendering;

use Softworx\RocXolid\Rendering\Services\Contracts\RenderingService;

/**
 * Base abstract renderable class.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractRenderable implements Contracts\Renderable
{
    use Traits\CanBeRendered;

    /**
     * Reference to service for rendering views.
     *
     * @var \Softworx\RocXolid\Rendering\Services\Contracts\RenderingService
     */
    protected $rendering_service;

    /**
     * Constructor.
     *
     * @param \Softworx\RocXolid\Rendering\Services\Contracts\RenderingService $rendering_service
     */
    public function __construct(RenderingService $rendering_service)
    {
        $this->rendering_service = $rendering_service;
    }

    /**
     * Retrieve the rendering service responsible for retrieving and composing the views.
     *
     * @return \Softworx\RocXolid\Rendering\Services\Contracts\RenderingService
     */
    protected function getRenderingService(): RenderingService
    {
        return $this->rendering_service;
    }
}
