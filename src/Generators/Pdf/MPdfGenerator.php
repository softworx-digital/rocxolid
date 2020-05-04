<?php

namespace Softworx\RocXolid\Generators\Pdf;

use Mpdf\Mpdf;
use Illuminate\Support\Collection;
// rocXolid pdf generators
use Softworx\RocXolid\Generators\Pdf\AbstractPdfGenerator;

/**
 * Enables object to have a model assigned.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
class MPdfGenerator extends AbstractPdfGenerator
{
    /**
     * {@inheritDoc}
     */
    public function init(?Collection $options = null): Contracts\PdfGenerator
    {
        $this->generator = app(Mpdf::class);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(Contracts\PdfDataProvider $provider): string
    {
        $this->generator->WriteHTML($this->getContent());

        return $this->generator->Output($provider->provideFilename(), \Mpdf\Output\Destination::STRING_RETURN);
    }
}
