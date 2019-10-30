<?php

namespace Softworx\RocXolid\Http\Controllers\Contracts;

/**
 * Enables object to restrict user access to certain actions.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 * @todo: put this to middleware?
 */
interface Permissionable
{
    /**
     * Check if user is permitted to given action.
     * 
     * @param string $action
     * @return bool
     */
    public function userCan(string $action): bool;
}
