<?php

namespace Softworx\RocXolid\Traits;

use Illuminate\Http\Request;
use Softworx\RocXolid\Contracts\Requestable as RequestableContract;

/**
 * Enables object to have a request assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Requestable
{
    /**
     * @var \Illuminate\Http\Request $request Assigned request reference.
     */
    protected $request;

    /**
     * {@inheritdoc}
     */
    public function setRequest(Request $request): RequestableContract
    {
        $this->request = $request;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest(): Request
    {
        if (!$this->hasRequest()) {
            throw new \UnderflowException(sprintf('No request set in [%s]', get_class($this)));
        }

        return $this->request;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRequest(): bool
    {
        return isset($this->request);
    }
}
