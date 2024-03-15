<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\FileGenerators;

use App;
use App\Exceptions\Custom\FileGeneratorException;
use App\Interfaces\FileGenerators\ArchiveGeneratorServiceInterface;
use ZipArchive;

/**
 * Zip Generator Service
 *
 * @package \App\Services\FileGenerators
 */
class ZipGeneratorService extends BaseFileGeneratorService implements ArchiveGeneratorServiceInterface
{
    /**
     * CsvGeneratorService constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string       $fileName
     * @param string|array $files
     * @return string|bool
     */
    public function create(string $fileName, string|array $files) : string|bool
    {
        try {
            $path = config('filesystems.disks.local_tmp.root') . $this->ds . uniqid($fileName . '_') . '.zip';

            /*
             * Generate file
             */
            $zip = new ZipArchive();

            $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            foreach ((array) $files as $file) {
                if (file_exists($file) && !is_dir($file)) {
                    $zip->addFile($file, basename($file));
                }
            }

            $zip->close();

            return $path;
        } catch (\Exception $exception) {
            if (!App::environment('production')) {
                throw new FileGeneratorException($exception->getMessage(), $exception->getCode(), $exception);
            }

            return false;
        }
    }
}
