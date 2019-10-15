<?php

namespace Softworx\RocXolid\Traits;

use Illuminate\Database\Eloquent\Model;
use Softworx\RocXolid\Contracts\Modellable as ModellableContract;

/**
 * Enables object to have a model assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Modellable
{
    /**
     * @var \Illuminate\Database\Eloquent\Model $model Assigned model reference.
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function setModel(Model $model): ModellableContract
    {
        $this->model = $model;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getModel(): Model
    {
        if (!$this->hasModel()) {
            throw new \UnderflowException(sprintf('No model set in [%s]', get_class($this)));
        }

        return $this->model;
    }

    /**
     * {@inheritdoc}
     */
    public function hasModel(): bool
    {
        return isset($this->model);
    }
}
