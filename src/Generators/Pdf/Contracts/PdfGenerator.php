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
     * Set HTML header.
     *
     * @param string|null $header
     * @return \Softworx\RocXolid\Generators\Pdf\Contracts\PdfGenerator
     */
    public function setHeader(?string $header): PdfGenerator;

    /**
     * Get HTML header.
     *
     * @return string
     */
    public function getHeader(): string;

    /**
     * Check if header is set.
     *
     * @return bool
     */
    public function hasHeader(): bool;

    /**
     * Set HTML footer.
     *
     * @param string|null $footer
     * @return \Softworx\RocXolid\Generators\Pdf\Contracts\PdfGenerator
     */
    public function setFooter(?string $footer): PdfGenerator;

    /**
     * Get HTML footer.
     *
     * @return string
     */
    public function getFooter(): string;

    /**
     * Check if footer is set.
     *
     * @return bool
     */
    public function hasFooter(): bool;

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
