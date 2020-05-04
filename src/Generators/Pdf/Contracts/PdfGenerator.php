<?php

namespace Softworx\RocXolid\Generators\Pdf\Contracts;

use Illuminate\Support\Collection;
// rocXolid pdf generator contracts
use Softworx\RocXolid\Generators\Pdf\Contracts\PdfDataProvider;

/**
 * Generates PDF from HTML.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
interface PdfGenerator
{
    /**
     * Initialize generator with given options.
     *
     * @param \Illuminate\Support\Collection|null $options Generator options.
     * @return \Softworx\RocXolid\Generators\Pdf\Contracts\PdfGenerator
     */
    public function init(?Collection $options = null): PdfGenerator;

    /**
     * Get PDF content.
     *
     * @param \Softworx\RocXolid\Generators\Pdf\Contracts\PdfDataProvider $provider PDF data provider.
     * @return string
     */
    public function generate(PdfDataProvider $provider): string;

    /**
     * Set HTML content to generate.
     *
     * @param string $content HTML content to be generated.
     * @return \Softworx\RocXolid\Generators\Pdf\Contracts\PdfGenerator
     */
    public function setContent(string $content): PdfGenerator;

    /**
     * Get HTML content to generate.
     *
     * @return string
     */
    public function getContent(): string;
}
