<?php

namespace Softworx\RocXolid\Components\Contracts\Features;

use Illuminate\Support\Collection;

/**
 * Allows component to be tabbed.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Tabbed
{
    /**
     * Retrieve tabs definition.
     *
     * @return \Illuminate\Support\Collection
     */
    public function tabs(): Collection;

    /**
     * Obtain (translated) tab title.
     *
     * @param string $tab
     * @return string
     */
    public function tabTitle(string $tab): string;

    /**
     * Obtain route to given tab.
     *
     * @param string $tab
     * @param array $params
     * @return string
     */
    public function tabRoute(string $tab, array $params = [], bool $force = false): string;

    /**
     * Check if given tab is a default one.
     *
     * @param string $tab
     * @return boolean
     */
    public function isDefaultTab(string $tab): bool;
}
