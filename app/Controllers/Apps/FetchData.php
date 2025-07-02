<?php

namespace App\Controllers\Apps;

use App\Controllers\BaseController;
use App\Models\Apps\AppsModel;
use CodeIgniter\HTTP\ResponseInterface; 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use DateTime;

class FetchData extends BaseController
{
    use ResponseTrait;
    public function __construct()
    {
        $this->apps = new AppsModel();
        $sess = session()->get();

    }

    public function fetchLayanan(){
        $sess = session()->get();
        $user = $sess['username'];

        $request = $this->request->getJSON();
        $keyword = $request->keyword ?? '';
        $unit    = $request->unit ?? 0;
        return $this->response->setStatusCode(200)->setJSON([
            'status' => 'success',
            'list'  => $this->apps->getLayananData($user, $keyword, $unit)
        ]);
    }

    public function fetchLayananByNIP(){
        $sess = session()->get();
        $user = $sess['username'];
        return $this->response->setStatusCode(200)->setJSON([
            'status' => 'success',
            'list'  => $this->apps->getLayananEnrolledData($user)
        ]);
    }

    public function enrolltask(){
        $sess = session()->get();
        $user = $sess['username'];

        $request    = $this->request->getJSON();
        $enroll_id  = $request->enrolled ?? '';

        $validate   = $this->apps->validateEnrolled($user,$enroll_id);
        if (!empty($validate)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Task sudah di enroll, silahkan cek pada menu My Task'
            ]);
        }

        $data = array('nip' => $user,'layanan_id' => $enroll_id);
        $this->apps->storeData($data, 'trx_enroll');
        return $this->response->setStatusCode(200)->setJSON([
            'status' => 'success',
            'message' => 'Task has been enrolled',
        ]);
    }

}
