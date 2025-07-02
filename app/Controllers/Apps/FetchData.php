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

        return $this->response->setJSON([
            'list'  => $this->apps->getLayananData($user, $keyword, $unit)
        ]);
    }

}
