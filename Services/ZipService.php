<?php

declare(strict_types=1);

namespace Modules\Cms\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use function Safe\ini_set;

/**
 * Class ZipService.
 */
class ZipService
{
    public string $filename_zip;

    private static ?self $instance = null;

    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \ReflectionException
     *
     * @return string|\Symfony\Component\HttpFoundation\BinaryFileResponse|void
     */
    public static function fromRowsPdf(array $params)
    {
        ini_set('max_execution_time', '3600');
        ini_set('memory_limit', '-1');
        $pdforientation = 'P';
        $out = isset($params['out']) ? $params['out'] : '';
        extract($params);
        if (! isset($pdf_view)) {
            dddx(['err' => 'pdf_view is missing']);

            return;
        }
        if (! isset($filename_zip)) {
            dddx(['err' => 'filename_zip is missing']);

            return;
        }
        if (! isset($rows)) {
            dddx(['err' => 'rows is missing']);

            return;
        }
        $pdf_parz = [
            'pdforientation' => $pdforientation,
            'view' => $pdf_view,
            'out' => 'content_PDF',
        ];
        // $filename_zip = '_'.$this->year.'.zip';

        $zip = new \ZipArchive();
        $filename_zip = Storage::disk('cache')->path($filename_zip);
        $filename_zip = str_replace(['/', '\\'], [\DIRECTORY_SEPARATOR, \DIRECTORY_SEPARATOR], $filename_zip);

        if (true !== $zip->open($filename_zip, \ZipArchive::CREATE)) {
            throw new \Exception('cannot create zip ['.$filename_zip.']');
        }

        // dddx(get_class_methods($zip));
        /*
        0 => "open"
        1 => "setPassword"
        2 => "close"
        3 => "count"
        4 => "getStatusString"
        5 => "addEmptyDir"
        6 => "addFromString"
        7 => "addFile"
        8 => "addGlob"
        9 => "addPattern"
        10 => "renameIndex"
        11 => "renameName"
        12 => "setArchiveComment"
        13 => "getArchiveComment"
        14 => "setCommentIndex"
        15 => "setCommentName"
        16 => "getCommentIndex"
        17 => "getCommentName"
        18 => "deleteIndex"
        19 => "deleteName"
        20 => "statName"
        21 => "statIndex"
        22 => "locateName"
        23 => "getNameIndex"
        24 => "unchangeArchive"
        25 => "unchangeAll"
        26 => "unchangeIndex"
        27 => "unchangeName"
        28 => "extractTo"
        29 => "getFromName"
        30 => "getFromIndex"
        31 => "getStream"
        32 => "setExternalAttributesName"
        33 => "setExternalAttributesIndex"
        34 => "getExternalAttributesName"
        35 => "getExternalAttributesIndex"
        36 => "setCompressionName"
        37 => "setCompressionIndex"
        38 => "setEncryptionName"
        39 => "setEncryptionIndex"
        */
        if (0 === $rows->count()) {
            return '<h3>Non ci sono file da aggiungere</h3>';
        }
        foreach ($rows as $row) {
            $panel = PanelService::make()->get($row);
            // Strict comparison using === between null and Modules\Xot\Contracts\PanelContract will always evaluate to false.
            // if (null === $panel) {
            //    return;
            // }
            // dddx($panel);
            // $filename = 'Perf_ind_'.$row->id.'_'.$row->matr.'_'.$row->cognome.'_'.$row->nome.'_'.$row->anno.'_'.date('Ymd').'.pdf';
            $filename = $panel->pdfFilename();

            $path = Storage::disk('cache')->path($filename);
            $path = str_replace(['/', '\\'], [\DIRECTORY_SEPARATOR, \DIRECTORY_SEPARATOR], $path);

            if (! File::exists($path)) {
                $pdf_parz['filename'] = $filename;
                $pdf_content = $panel->pdf($pdf_parz);
                // 134    Parameter #2 $contents of method Illuminate\Filesystem\FilesystemAdapter::put()
                // expects
                // Illuminate\Http\File|Illuminate\Http\UploadedFile|Psr\Http\Message\StreamInterface|resource|string,
                // mixed   given.
                $res = Storage::disk('cache')->put($filename, $pdf_content);
            }
            $zip->addFile($path, $filename);
        }
        $zip->close();

        if ('download' === $out) {
            return response()->download($filename_zip);
        }

        return '<h3>variabile Out non conosciuta</h3>';
    }

    public static function setFilenameZip(string $filename_zip): self
    {
        $instance = self::getInstance();
        $instance->filename_zip = $filename_zip;

        return $instance;
    }

    public static function getFilenameZip(): string
    {
        $instance = self::getInstance();

        return $instance->filename_zip;
    }

    public static function getFilenameZipPath(): string
    {
        $filename_zip = self::getFilenameZip();
        $filename_zip = Storage::disk('cache')->path($filename_zip);
        $filename_zip = str_replace(['/', '\\'], [\DIRECTORY_SEPARATOR, \DIRECTORY_SEPARATOR], $filename_zip);
        File::makeDirectory(\dirname($filename_zip), 0755, true, true);

        return $filename_zip;
    }

    public static function getZipArchive(): \ZipArchive
    {
        $zip = new \ZipArchive();
        $filename_zip = self::getFilenameZipPath();
        if (File::exists($filename_zip)) {
            File::delete($filename_zip);
        }
        if (true !== $zip->open($filename_zip, \ZipArchive::CREATE)) {
            throw new \Exception('cannot create zip ['.$filename_zip.']');
        }

        return $zip;
    }

    /**
     * Undocumented function.
     */
    public static function fromFiles(array $files, ?array $names): self
    {
        $zip = self::getZipArchive();
        foreach ($files as $index => $path) {
            $path = str_replace(['/', '\\'], [\DIRECTORY_SEPARATOR, \DIRECTORY_SEPARATOR], $path);
            if (isset($names)) {
                $zip->addFile($path, $names[$index]);
            } else {
                $zip->addFile($path, basename($path));
            }
        }

        $zip->close();

        return self::getInstance();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download()
    {
        $filename_zip = self::getFilenameZipPath();

        return response()->download($filename_zip);
    }

    // -- potrebbe non fare download ma mostra un bottone.. o altro
}
