<?php

namespace Softworx\RocXolid\Http\Responses\Contracts;

// use Illuminate\Http\Response;

/**
 * Represents HTTP response.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Response
{
    /**
     * Return the response
     *
     * @return string
     */
    public function get(): string;
}
