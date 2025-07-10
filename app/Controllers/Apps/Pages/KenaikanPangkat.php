<?php

namespace App\Controllers\Apps\Pages;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Pages\KPModel;
use App\Models\Apps\AppsModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KenaikanPangkat extends BaseController
{

    public function __construct()
    {
        $this->kpmodel = new KPModel();
        $this->apps = new AppsModel();
        $sess = session()->get();
    }

    public function upload(){
        $sess = session()->get();
        $data = array(
            'title'     => 'Upload KP',
            'seslog'    => session()->get(),
            'datalist'  => $this->kpmodel->getDataUploaded()
        );
        return $this->renderView('Apps/pages/services/kenaikanpangkat/upload', $data);
    }

    public function entry(){
        $sess = session()->get();
        $data = array(
            'title'     => 'Entry Data KP',
            'seslog'    => session()->get(),
            'datalist'  => $this->kpmodel->getCurrentData($sess['username']),
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

    public function getDataEntryPage(){
        $sess    = session()->get();
        $builder = $this->kpmodel->getCurrentData($sess['username']);
        $columns = ['nip','nama','instansi_temp','jenis_prosedur','jenis_kp','verified_by','received_date'];
        $result  = $this->dataTables->render($builder, $columns);
        return $this->response->setJSON($result);
    }    

    public function storeMasterData(){
        $sess = session()->get();
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
                'received_date'  => $receivedDate,
                'status'         => 0,
                'created_by'     => $sess['userid'],
            ];
        }

        if (!empty($dataBatch)) {
            $this->apps->insertBatchData($dataBatch, 'txn_layanan_kp');
        }

        // $this->allocateTask();

        return $this->response->setJSON([
            'status' => 'success',
            'filename' => $newName,
            'original_name' => $file->getClientName(),
            'datalist'  => $data
        ]);
    }

    public function allocateTask(){
        $taskDate = $this->kpmodel->getAvaData()->taskdate;
        $totalAvailable = (int) $this->kpmodel->getAvaData()->total;
        if ($totalAvailable < 1) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'No available data to allocate.'
            ]);
        }

        $memberEnrolled = $this->kpmodel->getEnrolledTask();
        if (!$memberEnrolled) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'No participant data found.'
            ]);
        }

        $processed = [];
        $totalFloor = 0;
        foreach ($memberEnrolled as $p) {
            $ideal = ($p['target'] / 100) * $totalAvailable;
            $floor = floor($ideal);
            $desimal = $ideal - $floor;
            $processed[] = [
                'task_date' => $taskDate,
                'layanan'   => $p['layanan'],
                'nip'       => $p['nip'],
                'nama'      => $p['nama'],
                'target'    => $p['target'],
                'available' => $totalAvailable,
                'ideal'     => round($ideal, 4),
                'floor'     => $floor,
                'desimal'   => $desimal
            ];
            $totalFloor += $floor;
        }

        $sisa = $totalAvailable - $totalFloor;
        usort($processed, function ($a, $b) {
            return $b['desimal'] <=> $a['desimal'];
        });

        // for ($i = 0; $i < $sisa; $i++) {
        //     $processed[$i]['floor'] += 1;
        // }

        $allocation = [];
        foreach ($processed as $p) {
            $allocation[] = [
                'task_date' => $p['task_date'],
                'layanan_id'=> 8,                
                'total'     => $p['available'],                
                'nip'       => $p['nip'],
                'target'    => $p['target'],
                'allocated' => $p['floor']
            ];
        }

        if (!empty($allocation)) {
            $this->apps->insertBatchData($allocation, 'txn_layanan_kp_target');
        }

        return $this->response->setJSON([
            'status' => true,
            'message'   => 'Task has been allocated', 
            'total_available' => $totalAvailable,
            'allocation' => $allocation
        ]);
    } 

    public function pullTask(){
        $sess = session()->get();
        $check_allocated = $this->kpmodel->getAllocatedTask($sess['username']);
        if (!$check_allocated) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'No allocated task data found.'
            ]);
        }

        // $limit_qouta -> disini tambahin validate untuk limitasi qouta dari allocated yang telah di tetapkan  

        $data = [];
        foreach ($check_allocated as $item) {
            $data[] = [
                'id' => $item['id'],
                'verified_by' => $sess['username']
            ];
        }

        $this->apps->updateBatchData($data, 'txn_layanan_kp', 'id');
        return $this->response->setJSON([
            'status' => true,
            'message'   => 'Task has been pulled', 
        ]);

    }
}