<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\FileGenerators;

use App;
use App\Exceptions\Custom\FileGeneratorException;
use App\Interfaces\FileGenerators\PdfGeneratorServiceInterface;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Arr;

/**
 * Snappy Pdf Generator Service
 *
 * @package \App\Services\FileGenerators
 */
class SnappyPdfGeneratorService extends BaseFileGeneratorService implements PdfGeneratorServiceInterface
{
    /**
     * @var array
     */
    protected array $options;

    /**
     * SnappyPdfGeneratorService constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->options = config('snappy.pdf.options');
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

            $path = $basePath . $this->ds . uniqid($fileName . '_') . '.pdf';

            /*
             * Generate file
             */
            SnappyPdf::loadHTML($html)
                ->setOptions(array_merge($this->options, Arr::except($options, ['base_path'])))
                ->save($path, true);

            if ($returnAsBase64) {
                return base64_encode_file($path, true);
            }

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
