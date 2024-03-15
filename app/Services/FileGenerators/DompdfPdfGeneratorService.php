<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\FileGenerators;

use App\Interfaces\FileGenerators\PdfGeneratorServiceInterface;
use Illuminate\Support\Facades\App;

/**
 * Dompdf Pdf Generator Service
 *
 * @package \App\Services\FileGenerators
 */
class DompdfPdfGeneratorService extends BaseFileGeneratorService implements PdfGeneratorServiceInterface
{
    /**
     * @var array
     */
    protected array $options;

    /**
     * DompdfPdfGeneratorService constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->options = (array) config('dompdf.options');
    }

    /**
     * @param string $fileName
     * @param mixed  $html
     * @param array  $options
     * @param bool   $returnAsBase64
     * @return string|bool
     */
    public function createFromHtml(string $fileName, mixed $html, array $options = [], bool $returnAsBase64 = false) : string|bool
    {
        try {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($html);

            /*
             * Prepare options
             */
            foreach (array_merge($this->options, $options) as $option => $value) {
                $pdf->setOption($option, $value);
            }

            $pdf->render();

            /*
             * Add page numbers
             */
            $domPdf = $pdf->getDomPDF();
            $canvas = $domPdf->get_canvas();

            // Set in header
//            $canvas->page_text($canvas->get_width() / 2, 10, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, [0, 0, 0]);
            // Set in footer
            $canvas->page_text($canvas->get_width() / 2, $canvas->get_height() - 20, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, [0, 0, 0]);

            if ($returnAsBase64) {
                return base64_encode($pdf->output());
            }

            /*
             * Generate file
             */
            $ds = DIRECTORY_SEPARATOR;

            $basePath = !empty($options['base_path']) ? $options['base_path'] : config('filesystems.disks.local_tmp.root');

            $path = $basePath . $ds . uniqid($fileName . '_') . '.pdf';

            $pdf->save($path);

            return $path;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @param string $templateName
     * @param mixed  $data
     * @param array  $options
     * @param bool   $returnAsBase64
     * @return mixed
     */
    public function createFromBlade(string $templateName, mixed $data, array $options = [], bool $returnAsBase64 = false) : mixed
    {
        $html = $this->getHtmlFromBlade($templateName, $data);

        if (!empty($options['file_name'])) {
            $templateName = $options['file_name'];

            unset($options['file_name']);
        }

        return $this->createFromHtml($templateName, $html, $options, $returnAsBase64);
    }
}
