<?php

namespace App\Libraries;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelUploader
{
    protected $uploadPath;

    public function __construct()
    {
        $this->uploadPath = ROOTPATH . 'public/uploads/excel/';
    }

    public function validateFile($file)
    {
        if (!$file || !$file->isValid()) {
            throw new \Exception('File tidak valid atau tidak terunggah.');
        }

        $allowedMimes = [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('Tipe file tidak didukung.');
        }

        $allowedExtensions = ['xls', 'xlsx'];
        if (!in_array($file->getExtension(), $allowedExtensions)) {
            throw new \Exception('Ekstensi file tidak diizinkan.');
        }
    }

    public function parseExcel($file)
    {
        $newName = $file->getRandomName();
        $file->move($this->uploadPath, $newName);
        $filePath = $this->uploadPath . $newName;
        $spreadsheet = IOFactory::load($filePath);
        $rows = $spreadsheet->getActiveSheet()->toArray();
        unlink($filePath);
        return $rows;
    }

    public static function cleanNumber($value)
    {
        return (int) str_replace([',', '.'], '', $value);
    }
}
