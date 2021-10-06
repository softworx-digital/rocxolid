<?php

namespace Softworx\RocXolid\Contracts;

/**
 * Enables object to have a simple parameter assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Paramable
{
    /**
     * Set the parameter.
     *
     * @param string $param Parameter value to assign.
     * @return \Softworx\RocXolid\Contracts\Paramable
     */
    public function setParam(string $param): Paramable;

    /**
     * Get the parameter value.
     *
     * @return string
     * @throws \UnderflowException If no param is set.
     */
    public function getParam(): string;

    /**
     * Check if the parameter is set.
     *
     * @return bool
     */
    public function hasParam(): bool;

    /**
     * Check if the parameter is equal to given param.
     *
     * @param string $param
     * @return bool
     */
    public function isParam(string $param): bool;
}
