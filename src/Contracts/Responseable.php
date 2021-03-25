<?php

namespace Softworx\RocXolid\Contracts;

use Softworx\RocXolid\Http\Responses\Contracts\Response;

/**
 * Enables object to have a response assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Responseable
{
    /**
     * Set the response.
     *
     * @param \Softworx\RocXolid\Http\Responses\Contracts\Response
     * @return \Softworx\RocXolid\Contracts\Responseable
     */
    public function setResponse(Response $response): Responseable;

    /**
     * Get the response.
     *
     * @return \Softworx\RocXolid\Http\Responses\Contracts\Response
     * @throws \UnderflowException If no response is set.
     */
    public function getResponse(): Response;

    /**
     * Check if the response is assigned.
     *
     * @return bool
     */
    public function hasResponse(): bool;
}
