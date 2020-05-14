<?php

namespace Softworx\RocXolid\Contracts;

use Illuminate\Http\Request;

/**
 * Enables object to have a request assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Requestable
{
    /**
     * Set the request.
     *
     * @param \Illuminate\Http\Request $request Request to be assigned.
     * @return \Softworx\RocXolid\Contracts\Requestable
     */
    public function setRequest(Request $request): Requestable;

    /**
     * Get the assigned request.
     *
     * @return \Illuminate\Http\Request
     */
    public function getRequest(): Request;

    /**
     * Check if the request is assigned.
     *
     * @return bool
     */
    public function hasRequest(): bool;
}
