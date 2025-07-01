<?php

namespace App\Controllers\Apps;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Apps\AppsModel;
use App\Models\Apps\PresenceModel;

class AppsController extends BaseController
{

    public function __construct()
    {
        $this->presence = new PresenceModel();
        $this->apps = new AppsModel();
    }

    // public function xxx(){
    //     $data = $this->apps->getDataPegawai();
    //     foreach ($data as $key => $value) {
    //         $newlogin = [
    //             'username' => $value['nip'],
    //             'fullname' => $value['nama'],
    //             'email'    => 'noset@email.com',
    //             'password' => password_hash($value['nip'], PASSWORD_DEFAULT),
    //             'role'     => 'USR',
    //             'status'   => 1,
    //             'created_at' => date('Y-m-d H:i:s'),
    //         ];

    //         $storedID = $this->presence->storeData($newlogin, 'auth_users');
    //         $usrPermissions = [6,7,8,10,11,12];        
    //         $authzData = [];
    //         foreach ($usrPermissions as $permission) {
    //             $authzData[] = [
    //                 'user_id'       => $storedID,
    //                 'permission_id' => $permission
    //             ];
    //         }
    //         $this->presence->insertBatchData($authzData, 'auth_users_permissions');
    //     }

    //     return $this->response->setJSON([
    //         'status'  => true,
    //         'message' => 'User has been created with permissions!'
    //     ]);
    // }

    public function home()
    {
        $data = array(
            'title'     => 'Home',
            'seslog'    => session()->get()
        );
        return $this->renderView('Apps/PresensiDashboard', $data);
    }

    public function profil()
    {
        $data = array(
            'title'     => 'Profil Pegawai',
            'seslog'    => session()->get(),
            'profil'    => $this->apps->getInfoProfil(session()->get('username'))
        );
        return $this->renderView('Apps/PresensiProfil', $data);
    }

    public function dataPegawai()
    {
        $data = array(
            'title'     => 'Data Pegawai',
            'seslog'    => session()->get(),
            'datalist'  => $this->apps->getDataPegawai(),
            'unitlist'  => $this->apps->getUnitList(),
        );
        return $this->renderView('Apps/PresensiDataPegawai', $data);
    }

    public function dataUsers()
    {
        $data = array(
            'title'     => 'Data Pengguna',
            'seslog'    => session()->get(),
            'datalist'  => array(),
        );
        return $this->renderView('Apps/PresensiDataAccount', $data);
    }

    public function dataMovable()
    {
        $data = array(
            'title'      => 'Movable Absen',
            'seslog'     => session()->get(),
            'datalist'   => $this->apps->getDataMovable(),
            'datamember' => $this->apps->getDataPegawai(),
        );
        return $this->renderView('Apps/PresensiDataMovable', $data);
    }

    public function dataSetup()
    {
        $data = array(
            'title'     => 'Setup Presensi',
            'seslog'    => session()->get(),
            'infosetup' => $this->apps->getSetupBase(),
        );
        return $this->renderView('Apps/PresensiSetup', $data);
    }

    public function dataManualPresence()
    {
        $data = array(
            'title'     => 'Manual Absen (Perubahan Data)',
            'seslog'    => session()->get(),
            'infosetup' => $this->apps->getSetupBase(),
        );
        return $this->renderView('Apps/_blank', $data);
    }

    public function dataSchedule()
    {
        $data = array(
            'title'     => 'Data Jadwal Presensi Pegawai',
            'seslog'    => session()->get(),
            'infosetup' => $this->apps->getSetupBase(),
        );
        return $this->renderView('Apps/_blank', $data);
    }
}
