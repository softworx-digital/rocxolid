<?php

namespace Softworx\RocXolid\Models\Contracts;

use Illuminate\Support\Collection;

/**
 * Enables model instance (asset) to be resized to different dimensions.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Resizable
{
    /**
     * Retrieve dimensions that will be used for resizing.
     *
     * <directory> => [
     *     'width' => <width>
     *     'height' => <height>
     *     'method' => fit|resize|crop
     *
     * @return Illuminate\Support\Collection
     */
    public function getDimensions(): Collection;

    /**
     * Set model data related to resizing.
     *
     * @param Illuminate\Support\Collection $sizes
     * @return \Softworx\RocXolid\Models\Contracts\Resizable
     */
    public function setResizeData(Collection $sizes): Resizable;
}
