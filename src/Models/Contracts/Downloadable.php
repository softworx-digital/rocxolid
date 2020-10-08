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
     * @param string|null $param
     * @return string
     */
    public function content(?string $param = null): string;

    /**
     * Check if stored file is valid.
     *
     * @param string|null $param
     * @return bool
     */
    public function isFileValid(?string $param = null): bool;

    /**
     * Retrieve instance file storage path relative to the storage directory.
     *
     * @param string|null $param
     * @return string
     */
    public function getStorageRelativePath(?string $param = null): string;

    /**
     * Retrieve instance file storage path.
     *
     * @param string|null $param
     * @return string
     */
    public function getStoragePath(?string $param = null): string;

    /**
     * Retrieve URL to download
     *
     * @return string
     */
    public function getDownloadUrl(): string;
}
