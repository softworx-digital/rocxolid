<?php

namespace Softworx\RocXolid\Traits;

use Softworx\RocXolid\Contracts\Paramable as ParamableContract;

trait Paramable
{
    /**
     * @var string $param Parameter value holder.
     */
    protected $param;

    /**
     * {@inheritdoc}
     */
    public function setParam(string $param): ParamableContract
    {
        $this->param = $param;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParam(): string
    {
        if (!$this->hasParam()) {
            throw new \UnderflowException(sprintf('No parameter set for [%s]', get_class($this)));
        }

        return $this->param;
    }

    /**
     * {@inheritdoc}
     */
    public function hasParam(): bool
    {
        return isset($this->param);
    }
}
