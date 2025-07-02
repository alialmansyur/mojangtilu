<?php

namespace App\Controllers\Apps;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Apps\AppsModel;

class AppsController extends BaseController
{

    public function __construct()
    {
        $this->apps = new AppsModel();
    }

    public function dashboard()
    {
        $data = array(
            'title'     => 'My Task',
            'seslog'    => session()->get()
        );
        return $this->renderView('Apps/pages/taskme', $data);
    }

    public function explore()
    {
        $data = array(
            'title'     => 'Explore Task',
            'seslog'    => session()->get()
        );
        return $this->renderView('Apps/pages/taskexplore', $data);
    }

    public function resume()
    {
        $data = array(
            'title'     => 'Resume Task',
            'seslog'    => session()->get()
        );
        return $this->renderView('Apps/pages/taskresume', $data);
    }

    public function history()
    {
        $data = array(
            'title'     => 'History Task',
            'seslog'    => session()->get()
        );
        return $this->renderView('Apps/pages/taskhistory', $data);
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

}
