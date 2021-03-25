<?php

namespace Softworx\RocXolid\Traits;

use Softworx\RocXolid\Http\Responses\Contracts\Response;
use Softworx\RocXolid\Contracts\Responseable as ResponseableContract;

/**
 * Enables object to have a response assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Responseable
{
    /**
     * @var \Softworx\RocXolid\Http\Responses\Contracts\Response Response holder.
     */
    protected $response;

    /**
     * {@inheritdoc}
     */
    public function setResponse(Response $response): ResponseableContract
    {
        $this->response = $response;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(): Response
    {
        if (!$this->hasResponse()) {
            throw new \UnderflowException(sprintf('No response set in [%s]', get_class($this)));
        }

        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function hasResponse(): bool
    {
        return isset($this->response);
    }
}
