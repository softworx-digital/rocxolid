<?php

namespace Softworx\RocXolid\Generators\Pdf;

/**
 * Generates PDF from HTML.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
abstract class AbstractPdfGenerator implements Contracts\PdfGenerator
{
    /**
     * Library generator reference.
     *
     * @var mixed
     */
    protected $generator;

    /**
     * HTML header.
     *
     * @var string
     */
    protected $header;

    /**
     * HTML footer.
     *
     * @var string
     */
    protected $footer;

    /**
     * HTML content.
     *
     * @var string
     */
    protected $content;

    /**
     * {@inheritDoc}
     */
    public function setHeader(?string $header): Contracts\PdfGenerator
    {
        $this->header = $header;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * {@inheritDoc}
     */
    public function hasHeader(): bool
    {
        return filled($this->header);
    }

    /**
     * {@inheritDoc}
     */
    public function setFooter(?string $footer): Contracts\PdfGenerator
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFooter(): string
    {
        return $this->footer;
    }

    /**
     * {@inheritDoc}
     */
    public function hasFooter(): bool
    {
        return filled($this->footer);
    }

    /**
     * {@inheritDoc}
     */
    public function setContent(string $content): Contracts\PdfGenerator
    {
        $this->content = $content;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
