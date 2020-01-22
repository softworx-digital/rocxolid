<?php

namespace Softworx\RocXolid\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Package facade to access rocXolid packages service.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid\UserManagement
 * @version 1.0.0
 */
class Package extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'package.accessor';
    }
}
