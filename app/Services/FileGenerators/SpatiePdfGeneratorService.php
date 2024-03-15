<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\FileGenerators;

use App;
use App\Exceptions\Custom\FileGeneratorException;
use App\Interfaces\FileGenerators\PdfGeneratorServiceInterface;
use Spatie\Browsershot\Browsershot;

/**
 * Spatie Pdf Generator Service
 *
 * @package \App\Services\FileGenerators
 */
class SpatiePdfGeneratorService extends BaseFileGeneratorService implements PdfGeneratorServiceInterface
{
    /**
     * @var array
     */
    protected array $options;

    /**
     * SpatiePdfGeneratorService constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->options = (array) config('browsershot.pdf');
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
            $basePath = !empty($options['base_path']) ? $options['base_path'] : config('filesystems.disks.local_tmp.root');

            $pdf = Browsershot::html($html)
                ->showBackground();

            /*
             * Prepare options
             */
            if (!empty($options['margins'])) {
                $pdf->margins(
                    $options['margins']['top'] ?? 30,
                    $options['margins']['right'] ?? 10,
                    $options['margins']['bottom'] ?? 30,
                    $options['margins']['left'] ?? 10
                );
                unset($options['margins']);
            }

            if (isset($options['header-html']) || isset($options['footer-html'])) {
                $pdf->waitUntilNetworkIdle()
                    ->showBrowserHeaderAndFooter();

                if (isset($options['header-html'])) {
                    $pdf->headerHtml($options['header-html']);
                    unset($options['header-html']);
                }

                if (isset($options['footer-html'])) {
                    $pdf->footerHtml($options['footer-html']);
                    unset($options['footer-html']);
                }
            }

            foreach (array_merge($this->options, $options) as $option => $value) {
                if ($option === 'timeout') {
                    $pdf->timeout($value);
                } else {
                    $pdf->setOption($option, $value);
                }
            }

            /*
             * Generate file
             */
            if ($returnAsBase64) {
                return $pdf->base64pdf();
            }

            $path = $basePath . $this->ds . uniqid($fileName . '_') . '.pdf';

            $pdf->save($path);

            return $path;
        } catch (\Exception $exception) {
            if (!App::environment('production')) {
                throw new FileGeneratorException($exception->getMessage(), $exception->getCode(), $exception);
            }

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
