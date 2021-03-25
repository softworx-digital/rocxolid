<?php

namespace Softworx\RocXolid\Generators\Pdf;

use Spipu\Html2Pdf\Html2Pdf;
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
class Html2PdfGenerator extends AbstractPdfGenerator
{
    /**
     * {@inheritDoc}
     */
    public function init(?Collection $options = null): Contracts\PdfGenerator
    {
        $this->generator = new Html2Pdf('P', 'A4', 'en', true, 'UTF-8', [ 0, 0, 0, 0 ]);
        $this->generator->setTestTdInOnePage(false);
        // $this->generator->setDefaultFont('arialunicid0');

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(Contracts\PdfDataProvider $provider): string
    {
        return $this->generator
            ->writeHTML($this->getContent())
            ->output('document.pdf', 'S');
    }
}
