<?php

namespace Softworx\RocXolid\Models\Contracts;

use Illuminate\Support\Collection;

/**
 * Enables model instance to be downloaded.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Downloadable
{
    /**
     * Return instance file content.
     *
     * @param string $param
     * @return string
     */
    public function content(string $param = null): string;

    /**
     * Retrieve instance file storage path relative to the storage directory.
     *
     * @param string $param
     * @return string
     */
    public function getStorageRelativePath(string $param = null): string;

    /**
     * Retrieve instance file storage path.
     *
     * @param string $param
     * @return string
     */
    public function getStoragePath(string $param = null): string;

    /**
     * Retrieve URL to download
     *
     * @return string
     */
    public function getDownloadUrl(): string;
}
