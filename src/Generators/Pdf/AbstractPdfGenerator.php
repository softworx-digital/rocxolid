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
     * HTML content.
     *
     * @var string
     */
    protected $content;

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
