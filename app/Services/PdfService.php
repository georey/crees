<?php

namespace App\Services;

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class PdfService
{
    protected $mpdf;

    public function __construct(array $config = [])
    {
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $this->mpdf = new Mpdf(array_merge([
            
            'default_font' => 'examplefont'
        ], $config));
    }

public function generatePdf($html, $fileName = 'document.pdf', $outputMode = 'D')
{
    $this->mpdf->WriteHTML($html);
    return $this->mpdf->Output($fileName, $outputMode);
}
}
