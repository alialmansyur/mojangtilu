<?php

namespace App\Controllers\Apps\Pages;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Pages\KPModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KenaikanPangkat extends BaseController
{

    public function __construct()
    {
        $this->apps = new KPModel();
        $sess = session()->get();
    }

    public function upload(){
        $sess = session()->get();
        $data = array(
            'title'     => 'Upload KP',
            'seslog'    => session()->get()
        );
        return $this->renderView('Apps/pages/services/kenaikanpangkat/upload', $data);
    }

    public function entry(){
        $sess = session()->get();
        $data = array(
            'title'     => 'Entry Data KP',
            'seslog'    => session()->get()
        );
        return $this->renderView('Apps/pages/services/kenaikanpangkat/entry', $data);
    }

    public function info(){
        $sess = session()->get();
        $data = array(
            'title'     => 'Informasi Pekerjaan',
            'seslog'    => session()->get()
        );
        return $this->renderView('Apps/pages/services/kenaikanpangkat/info', $data);
    }

    public function storeMasterData(){
        $file = $this->request->getFile('file');

        if (!$file->isValid()) {
            return $this->response->setStatusCode(400)->setBody('File tidak valid.');
        }

        $mime = $file->getMimeType();
        $allowedMimes = [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];

        if (!in_array($mime, $allowedMimes)) {
            return $this->response->setStatusCode(415)->setBody('Tipe file tidak valid.');
        }

        $allowedExtensions = ['xls', 'xlsx'];
        $extension = $file->getExtension();

        if (!in_array($extension, $allowedExtensions)) {
            return $this->response->setStatusCode(415)->setBody('Hanya file Excel yang diizinkan.');
        }

        $newName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/uploads/excel/', $newName);
        $filePath = ROOTPATH . 'public/uploads/excel/' . $newName;

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();
        unlink($filePath);

        $dataBatch = [];
        for ($i = 1; $i < count($data); $i++) {
            $row = $data[$i];
            if (empty($row[0])) {
                continue;
            }

            if (!empty($row[6])) {
                if (is_numeric($row[6])) {
                    $receivedDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[6])->format('Y-m-d');
                } else {
                    $timestamp = strtotime($row[6]);
                    $receivedDate = $timestamp ? date('Y-m-d', $timestamp) : null;
                }
            } else {
                $receivedDate = null;
            }

            $instansiID = $this->apps->getInstansiID($row[4]);
            $dataBatch[] = [
                'nip'            => $row[0],
                'nama'           => $row[1],
                'jenis_kp'       => $row[2],
                'jenis_prosedur' => $row[3],
                'instansi_temp'  => $row[4],
                'instansi_id'    => $instansiID,
                'verified_by'    => $row[5],
                'received_date'  => $receivedDate
            ];
        }

        if (!empty($dataBatch)) {
            $this->apps->insertBatchData($dataBatch, 'txn_layanan_kp');
        }

        return $this->response->setJSON([
            'status' => 'success',
            'filename' => $newName,
            'original_name' => $file->getClientName(),
            'datalist'  => $data
        ]);
    }
}