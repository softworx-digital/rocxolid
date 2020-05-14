<?php

namespace Softworx\RocXolid\Traits;

use Softworx\RocXolid\Contracts\Controllable as ControllableContract;
use Softworx\RocXolid\Http\Controllers\AbstractController as Controller;

/**
 * Enables object to have a controller assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Controllable
{
    /**
     * @var \Illuminate\Routing\Controller $controller Assigned controller reference.
     */
    protected $controller;

    /**
     * {@inheritdoc}
     */
    public function setController(Controller $controller): ControllableContract
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getController(): Controller
    {
        if (!$this->hasController()) {
            throw new \UnderflowException(sprintf('No controller set in [%s]', get_class($this)));
        }

        return $this->controller;
    }

    /**
     * {@inheritdoc}
     */
    public function hasController(): bool
    {
        return isset($this->controller);
    }
}
