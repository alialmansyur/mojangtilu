<?php

namespace App\Controllers\Apps;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Apps\AppsModel;
use App\Models\Apps\PresenceModel;

class PresensiController extends BaseController
{
    public function __construct()
    {
        $this->presence = new PresenceModel();
        $this->apps = new AppsModel();
    }

    public function presensi()
    {
        $sess       = session()->get();
        $data = array(
            'title'     => 'Presensi Online',
            'seslog'    => session()->get(),
            'datalist'  => $this->presence->getHistoryPresence($sess['username']),
            'setuplist' => $this->presence->getTimeAttende()
        );
        return $this->renderView('Apps/PresensiGeoLocation', $data);
    }

    public function kalender()
    {
        $data = array(
            'title'     => 'Kalender Presensi',
            'seslog'    => session()->get()
        );
        return $this->renderView('Apps/_blank', $data);
    }

    public function riwayat()
    {
        $sess       = session()->get();
        $tempstart  = $this->request->getPost('start') ?? date('Y-m-d');
        $tempend    = $this->request->getPost('end') ?? date('Y-m-d');      
        $start      = date('Y-m-d', strtotime($tempstart)); 
        $end        = date('Y-m-d', strtotime($tempend));   

        $data = array(
            'title'     => 'Riwayat Presensi',
            'seslog'    => session()->get(),
            'datalist'  => $this->presence->getHistoryPresenceAll($sess['username'], $start, $end),
            'start' => $tempstart,
            'end'   => $tempend,
        );
        return $this->renderView('Apps/PresensiRiwayat', $data);
    }

    public function cuti()
    {
        $sess       = session()->get();
        $tempstart  = $this->request->getPost('start') ?? date('Y-m-d');
        $tempend    = $this->request->getPost('end') ?? date('Y-m-d');      
        $start      = date('Y-m-d', strtotime($tempstart));
        $end        = date('Y-m-d', strtotime($tempend));   

        $data = array(
            'title'     => 'Pengajuan Cuti',
            'seslog'    => session()->get(),
            'listcuti'  => $this->presence->getListCuti(),
            'datalist'  => $this->presence->getHistoryInstallment($sess['username'], $start, $end, 1),
            'start' => $tempstart,
            'end'   => $tempend,
        );
        return $this->renderView('Apps/PresensiInstallmentCuti', $data);
    }

    public function izin()
    {
        $sess       = session()->get();
        $tempstart  = $this->request->getPost('start') ?? date('Y-m-d');
        $tempend    = $this->request->getPost('end') ?? date('Y-m-d');      
        $start      = date('Y-m-d', strtotime($tempstart));
        $end        = date('Y-m-d', strtotime($tempend));   

        $data = array(
            'title'     => 'Pengajuan Izin',
            'seslog'    => session()->get(),
            'listcuti'  => $this->presence->getListIzin(),
            'datalist'  => $this->presence->getHistoryInstallment($sess['username'], $start, $end, 0),
            'start' => $tempstart,
            'end'   => $tempend,
        );
        return $this->renderView('Apps/PresensiInstallmentIzin', $data);
    }

    // public function jadwalkerja()
    // {
    //     $data = array(
    //         'title'     => 'Pengaturan Jadwal Kerja',
    //         'seslog'    => session()->get()
    //     );
    //     return $this->renderView('Apps/_blank', $data);
    // }

    public function approvalData()
    {
        $sess       = session()->get();
        $tempstart  = $this->request->getPost('start') ?? date('Y-m-d');
        $tempend    = $this->request->getPost('end') ?? date('Y-m-d');      
        $start      = date('Y-m-d', strtotime($tempstart));
        $end        = date('Y-m-d', strtotime($tempend));          
        $data = array(
            'title'     => 'Approval Cuti/Izin Pegawai',
            'seslog'    => session()->get(),
            'datalist'  => $this->presence->approvalInstallment($param = 0),
            'start'     => $tempstart,
            'end'       => $tempend,
        );
        return $this->renderView('Apps/PresensiApproval', $data);
    }
}
