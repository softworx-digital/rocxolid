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
    protected static $default_config = [
        'format' => 'A4',
        'default_font_size' => 0,
        'default_font' => '',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 16,
        'margin_bottom' => 16,
        'margin_header' => 9,
        'margin_footer' => 9,
        'orientation' => 'P',
    ];

    /**
     * {@inheritDoc}
     */
    public function init(?Collection $config = null): Contracts\PdfGenerator
    {
        $this->generator = app(Mpdf::class, [
            'config' => collect(static::$default_config)->merge($config)->toArray()
        ]);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(Contracts\PdfDataProvider $provider): string
    {
        $this->generator->WriteHTML($this->getContent());
        // $this->generator->setFooter("Page {PAGENO} of {nb}");

        return $this->generator->Output($provider->provideFilename(), \Mpdf\Output\Destination::STRING_RETURN);
    }
}
