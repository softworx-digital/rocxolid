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
        /*
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 80,
        'margin_bottom' => 16,
        'margin_header' => 9,
        'margin_footer' => 40,
        */
        'orientation' => 'P',
        'setAutoTopMargin' => 'stretch',
        'setAutoBottomMargin' => 'stretch',
        'shrink_tables_to_fit' => 1,
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
// dd($this->getContent());
// echo $this->getContent(); exit;
        $this->generator->WriteHTML($this->getContent());

        /*
        if ($this->hasHeader()) {
            // $this->generator->SetHeader($this->getHeader());
            $this->generator->SetHeader($this->getHeader(), 'O', true);
            $this->generator->SetHeader($this->getHeader(), 'E', true);
        }

        if ($this->hasFooter()) {
            $this->generator->SetHTMLFooter($this->getFooter());
        }
        */

        return $this->generator->Output($provider->provideFilename(), \Mpdf\Output\Destination::STRING_RETURN);
    }
}
