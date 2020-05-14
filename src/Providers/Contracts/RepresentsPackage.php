<?php

namespace Softworx\RocXolid\Providers\Contracts;

/**
 * Makes service provider a package representer.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface RepresentsPackage
{
    /**
     * Get package key.
     *
     * @return string
     */
    public static function getPackageKey(): string;

    /**
     * Get package title in current app locale.
     *
     * @return string
     */
    public static function getTitle(): string;
}
