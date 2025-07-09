<?php

namespace App\Controllers\Apps\Pages;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Pages\STKModel;
use App\Models\Apps\AppsModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Libraries\ExcelUploader;
use App\Libraries\DataTablesLib;

class StatistikData extends BaseController
{

    public function __construct()
    {
        $this->stkmodel = new STKModel();
        $this->apps = new AppsModel();
        $this->uploader = new ExcelUploader();
        $this->dataTables = new DataTablesLib();
        $sess = session()->get();
    }

    public function upload(){
        $sess = session()->get();
        $data = array(
            'title'     => 'Upload Statistik',
            'seslog'    => session()->get(),
            'jenis'     => $this->request->getPost('jenis') ?? '-',
            'datalist'  => array()
        );
        return $this->renderView('Apps/pages/services/statistikdata/upload', $data);
    }

    public function entry(){
        $sess = session()->get();
        $data = array(
            'title'     => 'Entry Data Statistik',
            'seslog'    => session()->get(),
        );
        return $this->renderView('Apps/pages/services/statistikdata/entry', $data);
    }

    public function info(){
        $sess = session()->get();
        $data = array(
            'title'     => 'Informasi Pekerjaan',
            'seslog'    => session()->get()
        );
        return $this->renderView('Apps/pages/services/statistikdata/info', $data);
    }

    public function getData(){
        $jenis = $this->request->getPost('jenis');
        $builder = $this->stkmodel->getDataLog($jenis);
        $columns = ['title', 'date', 'period', 'created_by', 'created_at'];
        $result = $this->dataTables->render($builder, $columns);
        return $this->response->setJSON($result);
    }

    public function removeData(){
        $sess = session()->get();
        $key  = $this->request->getPost('key', FILTER_SANITIZE_STRING);
        $jenis  = $this->request->getPost('jenis', FILTER_SANITIZE_STRING);
        $table = $this->getTableForJenis($jenis);
        $this->apps->removeData($key,'asn_log_inputs');
        $this->apps->removeDataLogStatistik($key,$table);
        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data Berhasil di hapus',
        ]);
    }

    public function storeData(){
        $sess     = session()->get();
        $jenis    = $this->request->getPost('jenis', FILTER_SANITIZE_STRING);
        $period   = $this->request->getPost('period', FILTER_SANITIZE_STRING);
        $syncdate = $this->request->getPost('syncdate');
        $file     = $this->request->getFile('attach');

        $rules = [
            'jenis'    => 'required',
            'period'   => 'required|regex_match[/^\d{4}-\d{2}$/]',
            'syncdate' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => $this->validator->getErrors()
            ]);
        }

        $this->uploader->validateFile($file);
        $dataLog = [
            'title'      => $jenis,
            'action'     => 'create',
            'date'       => $syncdate,
            'period'     => $period,
            'created_by' => $sess['username'],
        ];
        $insertID = $this->apps->storeData($dataLog, 'asn_log_inputs');

        $rows = $this->uploader->parseExcel($file);
        if (count($rows) < 1) {
            throw new \Exception('Data kosong atau format salah.');
        }

        $mappingFunction = $this->getMapperForJenis($jenis, $insertID, $syncdate);
        $table = $this->getTableForJenis($jenis);

        $dataBatch = [];
        foreach (array_slice($rows, 1) as $row) {
            if (empty($row[0])) continue;
            $dataBatch[] = $mappingFunction($row);
        }

        if ($dataBatch) {
            $this->apps->insertBatchData($dataBatch, $table);
        }

        return $this->response->setJSON([
            'status'   => 'success',
            'message'  => 'Upload dan import data berhasil.',
            'datalist' => $dataBatch
        ]);
    }

    private function getMapperForJenis($jenis, $insertID, $syncdate)
    {
        switch ($jenis) {
            case 'Jumlah ASN':
                return function ($row) use ($insertID, $syncdate) {
                    return [
                        'log_input_id'  => $insertID,
                        'kode_instansi' => $row[0],
                        'pns'           => ExcelUploader::cleanNumber($row[2]),
                        'pppk'          => ExcelUploader::cleanNumber($row[3]),
                        'jumlah'        => ExcelUploader::cleanNumber($row[4]),
                        'tanggal'       => $syncdate
                    ];
                };

            case 'Golongan ASN':
                return function ($row) use ($insertID, $syncdate) {
                    return [
                        'log_input_id'  => $insertID,
                        'kode_instansi' => $row[0],
                        'pns_gol_i'     => ExcelUploader::cleanNumber($row[2]),
                        'pns_gol_ii'    => ExcelUploader::cleanNumber($row[3]),
                        'pns_gol_iii'   => ExcelUploader::cleanNumber($row[4]),
                        'pns_gol_iv'    => ExcelUploader::cleanNumber($row[5]),
                        'pppk_gol_i'    => ExcelUploader::cleanNumber($row[6]),
                        'pppk_gol_ii'   => ExcelUploader::cleanNumber($row[7]),
                        'pppk_gol_iii'  => ExcelUploader::cleanNumber($row[8]),
                        'pppk_gol_iv'   => ExcelUploader::cleanNumber($row[9]),
                        'pppk_gol_v'    => ExcelUploader::cleanNumber($row[10]),
                        'pppk_gol_vi'   => ExcelUploader::cleanNumber($row[11]),
                        'pppk_gol_vii'  => ExcelUploader::cleanNumber($row[12]),
                        'pppk_gol_viii' => ExcelUploader::cleanNumber($row[13]),
                        'pppk_gol_ix'   => ExcelUploader::cleanNumber($row[14]),
                        'pppk_gol_x'    => ExcelUploader::cleanNumber($row[15]),
                        'pppk_gol_xi'   => ExcelUploader::cleanNumber($row[16]),
                        'pppk_gol_xii'  => ExcelUploader::cleanNumber($row[17]),
                        'pppk_gol_xiii' => ExcelUploader::cleanNumber($row[18]),
                        'pppk_gol_xiv'  => ExcelUploader::cleanNumber($row[19]),
                        'pppk_gol_xv'   => ExcelUploader::cleanNumber($row[20]),
                        'pppk_gol_xvi'  => ExcelUploader::cleanNumber($row[21]),
                        'pppk_gol_xvii' => ExcelUploader::cleanNumber($row[22]),
                        'jumlah'        => ExcelUploader::cleanNumber($row[23]),
                        'tanggal'       => $syncdate
                    ];
                };

            case 'Jenis Kelamin ASN':
                return function ($row) use ($insertID, $syncdate) {
                    return [
                        'log_input_id'  => $insertID,
                        'kode_instansi' => $row[0],
                        'pns_pria'      => ExcelUploader::cleanNumber($row[2]),
                        'pns_wanita'    => ExcelUploader::cleanNumber($row[3]),
                        'pppk_pria'     => ExcelUploader::cleanNumber($row[4]),
                        'pppk_wanita'   => ExcelUploader::cleanNumber($row[5]),
                        'jumlah'        => ExcelUploader::cleanNumber($row[6]),
                        'tanggal'       => $syncdate
                    ];
                };
            case 'Pendidikan ASN':
                return function ($row) use ($insertID, $syncdate) {
                    return [
                        'log_input_id'  => $insertID,
                        'kode_instansi' => $row[0],
                        'pns_sd'        => ExcelUploader::cleanNumber($row[2]),
                        'pns_smp'       => ExcelUploader::cleanNumber($row[3]),
                        'pns_sma'       => ExcelUploader::cleanNumber($row[4]),
                        'pns_d1'        => ExcelUploader::cleanNumber($row[5]),
                        'pns_d2'        => ExcelUploader::cleanNumber($row[6]),
                        'pns_d3'        => ExcelUploader::cleanNumber($row[7]),
                        'pns_s1'        => ExcelUploader::cleanNumber($row[8]),
                        'pns_s2'        => ExcelUploader::cleanNumber($row[9]),
                        'pns_s3'        => ExcelUploader::cleanNumber($row[10]),
                        'pppk_sd'       => ExcelUploader::cleanNumber($row[11]),
                        'pppk_smp'      => ExcelUploader::cleanNumber($row[12]),
                        'pppk_sma'      => ExcelUploader::cleanNumber($row[13]),
                        'pppk_d1'       => ExcelUploader::cleanNumber($row[14]),
                        'pppk_d2'       => ExcelUploader::cleanNumber($row[15]),
                        'pppk_d3'       => ExcelUploader::cleanNumber($row[16]),
                        'pppk_s1'       => ExcelUploader::cleanNumber($row[17]),
                        'pppk_s2'       => ExcelUploader::cleanNumber($row[18]),
                        'pppk_s3'       => ExcelUploader::cleanNumber($row[19]),
                        'jumlah'        => ExcelUploader::cleanNumber($row[20]),
                        'tanggal'       => $syncdate
                    ];
                };
            case 'Usia ASN':                
                return function ($row) use ($insertID, $syncdate) {
                    return [
                        'log_input_id'  => $insertID,
                        'kode_instansi' => $row[0],
                        'pns_kurang_sama_31'    => ExcelUploader::cleanNumber($row[2]),
                        'pns_31_40'             => ExcelUploader::cleanNumber($row[3]),
                        'pns_41_50'             => ExcelUploader::cleanNumber($row[4]),
                        'pns_lebih_sama_51'     => ExcelUploader::cleanNumber($row[5]),
                        'pppk_kurang_sama_31'   => ExcelUploader::cleanNumber($row[6]),
                        'pppk_31_40'            => ExcelUploader::cleanNumber($row[7]),
                        'pppk_41_50'            => ExcelUploader::cleanNumber($row[8]),
                        'pppk_lebih_sama_51'    => ExcelUploader::cleanNumber($row[9]),
                        'jumlah'                => ExcelUploader::cleanNumber($row[10]),
                        'tanggal'       => $syncdate
                    ];
                };

            default:
                throw new \Exception('Jenis tidak dikenali.');
        }
    }

    private function getTableForJenis($jenis)
    {
        switch ($jenis) {
            case 'Jumlah ASN':
                return 'asn_jumlahs';
            case 'Golongan ASN':
                return 'asn_golongans';
            case 'Jenis Kelamin ASN':
                return 'asn_jenis_kelamins';
            case 'Pendidikan ASN':
                return 'asn_pendidikans';
            case 'Usia ASN':
                return 'asn_usias';                                                
            default:
                throw new \Exception('Jenis tidak dikenali.');
        }
    }

}
