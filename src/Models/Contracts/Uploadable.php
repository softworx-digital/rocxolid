<?php

namespace Softworx\RocXolid\Models\Contracts;

use Illuminate\Http\UploadedFile;

/**
 * Enables model instance to be created or replaced by upload.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface Uploadable
{
    /**
     * Retrieve upload path relative to storage path.
     *
     * @return string
     */
    public function getRelativeUploadPath(): string;

    /**
     * Set model data related to uploaded physical file.
     *
     * @param Illuminate\Http\UploadedFile $uploaded_file
     * @param string $storage_path
     * @return \Softworx\RocXolid\Models\Contracts\Uploadable
     */
    public function setUploadData(UploadedFile $uploaded_file, string $storage_path): Uploadable;
}
