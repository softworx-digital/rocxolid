<?php

namespace Softworx\RocXolid\Generators\Pdf\Contracts;

/**
 * Provides data to generated PDF.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface PdfDataProvider
{
    /**
     * Provide file name to generated PDF.
     *
     * @return string
     */
    public function provideFilename(): string;
}
