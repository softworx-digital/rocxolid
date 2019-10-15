<?php

namespace Softworx\RocXolid\Traits;

use Illuminate\Http\Request;

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
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        if (!$this->hasRequest()) {
            throw new \UnderflowException(sprintf('No request set in [%s]', get_class($this)));
        }

        return $this->request;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRequest()
    {
        return isset($this->request);
    }
}
