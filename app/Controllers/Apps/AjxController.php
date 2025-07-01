<?php

namespace App\Controllers\Apps;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface; 
use App\Models\Apps\PresenceModel;
use App\Models\Apps\PresenceLocationModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use DateTime;

class AjxController extends BaseController
{

    use ResponseTrait;

    public function __construct()
    {
        $this->presence = new PresenceModel();
        $this->location = new PresenceLocationModel();
        $sess = session()->get();

    }

    public function killData(){
        $sess = session()->get();
        $key  = $this->request->getPost('key', FILTER_SANITIZE_STRING);
        $tableinfo  = $this->request->getPost('tableinfo', FILTER_SANITIZE_STRING);
        $tableDest  = "data_".$tableinfo;
        $this->presence->removeData($key,$tableDest);
        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data Berhasil di hapus',
        ]);
    }

    public function statusData(){
        $sess   = session()->get();
        $key    = $this->request->getPost('key', FILTER_SANITIZE_STRING);
        $status = $this->request->getPost('status',FILTER_SANITIZE_STRING);
        $tableinfo  = $this->request->getPost('tableinfo',FILTER_SANITIZE_STRING);
        $tableDest  = "data_".$tableinfo;
        $this->presence->updateData(array('is_status' => $status),$key,$tableDest);
        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Status Telah di Perbaharui',
        ]);
    }    

    public function approveInstallment(){
        $sess   = session()->get();
        $key    = $this->request->getPost('key', FILTER_SANITIZE_STRING);
        $status = $this->request->getPost('status',FILTER_SANITIZE_STRING);
        $this->presence->updateDataInstallment(array('status' => $status, 'approved_by' => $sess['userid'], 'approved_at' => date('Y-m-d H:i:s')),$key,'presence_app');
        $flgTxt = $status == 4 ? "Pengajuan Di Tolak" : "Pengajuan Disetujui";
        return $this->response->setJSON([
            'status'  => true,
            'message' => $flgTxt . ' , data telah di perbaharui',
        ]);
    }

    public function updateActiveMenu($menuId = null){
        session()->set('active_menus', $menuId);
        return $this->response->setStatusCode(200)->setJSON([
            'status' => 'success',
            'message' => 'Active menu updated',
            'active_menus' => $menuId
        ]);        
    }

    public function recognize(){
        $file = $this->request->getFile('image');

        if (!$file->isValid()) {
            return $this->response->setJSON(['error' => 'File tidak valid']);
        }

        $newName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/uploads/', $newName);

        $filePath = ROOTPATH . 'public/uploads/' . $newName;

        if (!file_exists($filePath)) {
            return $this->response->setJSON(['error' => 'File tidak ditemukan']);
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://go-api:8001/api/recognize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'image' => new \CURLFile($filePath),
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        if ($err) {
            return $this->response->setJSON(['error' => $err]);
        }

        $result = json_decode($response, true);
        return $this->response->setJSON($result);
    }

    public function uploadAvatar(){
        $sess = session()->get();
        $file = $this->request->getFile('avatar');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'File tidak valid atau tidak ada.'
            ]);
        }

        $newName = $sess['username'] . '.jpg';
        $uploadPath = ROOTPATH . 'public/uploads/avatar';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        $existingFile = $uploadPath . '/' . $newName;
        if (file_exists($existingFile)) {
            unlink($existingFile);
        }

        $file->move($uploadPath, $newName);
        $username = $sess['username'];
        $avatarUrl = 'uploads/avatar/' . $newName;

        $this->presence->updateByUsername(['userimage' => $avatarUrl], $username, 'auth_users');

        $localFilePath = $uploadPath . '/' . $newName;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://go-api:8001/api/register",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'username' => $username,
                'image'    => new \CURLFile($localFilePath),     
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        // Decode Go response
        $goResponse = null;
        if ($response) {
            $goResponse = json_decode($response, true);
        }

        // Response ke frontend
        if ($err) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to call Face API',
                'curl_error' => $err
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'success',
            'message'   => 'Avatar berhasil diupdate dan dikirim ke Face API.',
            'avatar'    => base_url($avatarUrl),
            'faceApi'   => $goResponse,
            'httpCode'  => $httpCode
        ]);
    }


    public function storePegawai(){
        $validationRules = [
            'nip'       => 'required|min_length[10]|is_unique[auth_users.username]',
            'fullname'  => 'required|min_length[3]|is_unique[auth_users.fullname]',
            'office'    => 'required',
            'position'  => 'required',
        ];

        if (!$this->validate($validationRules)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => $this->validator->getErrors(),
            ]);
        }

        $data = [
            'nip'   => $this->request->getPost('nip',FILTER_SANITIZE_STRING),
            'nama'   => $this->request->getPost('fullname',FILTER_SANITIZE_STRING),
            'base'   => $this->request->getPost('office',FILTER_SANITIZE_STRING),
            'posisi'   => $this->request->getPost('position',FILTER_SANITIZE_STRING)
        ];

        $auth = [
            'username' => $this->request->getPost('nip',FILTER_SANITIZE_STRING),
            'fullname' => $this->request->getPost('fullname',FILTER_SANITIZE_STRING),
            'email'    => 'noset@email.com',
            'password' => password_hash($this->request->getPost('nip'), PASSWORD_DEFAULT),
            'role'     => 'USR',
            'status'   => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $role = 'USR';
        $this->presence->storeData($data, 'presence_pegawai');
        $storedID = $this->presence->storeData($auth, 'auth_users');
        if ($storedID) {
            $admPermissions = [1,2,3,4,6,7,8,10,11,12,13,14];
            $usrPermissions = [6,7,8,10,11,12,13];
        
            $role = $this->request->getPost('role');
            $permissions = ($role === 'USR') ? $admPermissions : $usrPermissions;
        
            $authzData = [];
            foreach ($permissions as $permission) {
                $authzData[] = [
                    'user_id'       => $storedID,
                    'permission_id' => $permission,
                    'is_create' => 1,
                    'is_read'   => 1,
                    'is_update' => 1,
                    'is_delete' => 1
                ];
            }
            $this->presence->insertBatchData($authzData, 'auth_users_permissions');
        
            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Data pegawai berhasil di tambahkan'
            ]);
        } else {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Something went wrong.'
            ]);
        }
    }

    public function storeProfil(){
        $sess   = session()->get();
        $rules = [
            'nickname'      => 'required',
            'tempemail'    => 'required',
            'tempphone'     => 'required',
            'tempaddress'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => $this->validator->getErrors(),
            ]);
        }        

        $nickname   = $this->request->getPost('nickname', FILTER_SANITIZE_STRING);
        $email      = $this->request->getPost('tempemail',FILTER_SANITIZE_STRING);
        $phone      = $this->request->getPost('tempphone',FILTER_SANITIZE_STRING);
        $address    = $this->request->getPost('tempaddress',FILTER_SANITIZE_STRING);
        $this->presence->updateByUsername(array('nickname' => $nickname, 'phone' => $email, 'email' => $phone, 'address' => $address),$sess['username'],'data_member');
        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    public function storeMovable(){
        $sess   = session()->get();
        $rules = [
            'txt_date'      => 'required',
            'txt_reason'    => 'required',
            'is_member'     => 'required',
        ];

        $messages = [
            'txt_date'      => ['required' => 'Tanggal wajib diisi'],
            'txt_reason'    => ['required' => 'Alasan wajib dipilih'],
            'is_member'    => ['required' => 'Pilih minimal 1 pegawai'],
        ];

        $memberList = $this->request->getPost('is_member');
        if (empty($memberList) || !is_array($memberList)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Pilih minimal 1 pegawai untuk di-assign ke dalam movable presensi',
            ]);
        }

        $dataMemberList = [];
        foreach ($memberList as $item) {
            $dataMemberList[] = [
                'nip'           => trim($item),
                'presence_date' => $this->request->getPost('txt_date'),
                'is_status'     => 1,
                'created_by'    => $sess['userid']
            ];
        }
        $this->presence->insertBatchData($dataMemberList, 'data_movable');
        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    public function getPegawai(){
        $key    = $this->request->getPost('key');
        $data   = $this->presence->getPegawainfo($key);
        return json_encode($data);
    }

    public function storedatapresence()
    {
        $sess       = session()->get();
        $latitude   = $this->request->getPost('latitude',FILTER_SANITIZE_STRING);
        $longitude  = $this->request->getPost('longitude',FILTER_SANITIZE_STRING);
        $address    = $this->request->getPost('address',FILTER_SANITIZE_STRING);
        $city       = $this->request->getPost('city',FILTER_SANITIZE_STRING);
        $ctrycode   = $this->request->getPost('country_code',FILTER_SANITIZE_STRING);
        $state      = $this->request->getPost('state',FILTER_SANITIZE_STRING);
        $postcode   = $this->request->getPost('postcode',FILTER_SANITIZE_STRING);
        $road       = $this->request->getPost('road',FILTER_SANITIZE_STRING);
        $country    = $this->request->getPost('country',FILTER_SANITIZE_STRING);
        $status     = $this->request->getPost('status',FILTER_SANITIZE_STRING); // 1 IN 2 OUT
        $username   = $sess['username'];
        $curdate    = date('Y-m-d');
        $curtmdate  = date('Y-m-d H:i:s');

        $data_presence = array(
            'username' => $username,
            'presence_date' => $curdate,
            // 'start_attendence' => $curtmdate,
            'status' => $status
        );

        $data_device = array(
            'username' => $username,
            'presence_date' => $curdate,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'os' => $this->getOS(),
            'browser' => $this->getBrowser(),
        );

        $data_locate = array(
            'username' => $username,
            'presence_date' => $curdate,
            'location_id' => $status,
            'lat'   => $latitude,   
            'lan'   => $longitude,
            'full_address' => $address,
            'road'  => $road,
            'state_district' => $state,
            'city'  => $city,
            'postcode' => $ctrycode,
            'country' => $country
        );

        $data_loggng = array(
            'username' => $username,
            'presence_date' => $curdate,
            'status' => $status,
            'time_attendence' => $curtmdate,
        );        

        if ($status == 1) {
            $presence   = $this->presence->checkDataPresence($username);
            if (!$presence) {        
                $data_presence['start_attendence'] = $curtmdate;
                $presence = $this->presence->insertPresenceData($data_presence,'presence');
                $data_device['presence_id'] = $presence;
                $data_locate['presence_id'] = $presence;
                $data_loggng['presence_id'] = $presence;
                $this->presence->insertPresenceData($data_device,'presence_device');     
                $this->presence->insertPresenceData($data_locate,'presence_location');     
                $this->presence->insertPresenceData($data_loggng,'presence_log');     
                return $this->response->setStatusCode(200)->setJSON([
                    'status' => 'success',
                    'message' => 'Data berhasil di kirim',
                ]);                 
            }else{
                return $this->response->setStatusCode(200)->setJSON([
                    'status' => 'warning',
                    'message' => 'Terjadi kesalahan, harap coba kembali',
                ]);    
            }
        }elseif ($status == 2) {
            $presence   = $this->presence->checkDataPresence($username);
            $presence_info   = $this->presence->getDataPresence($username);
            if (!$presence) {
                $data_presence['end_attendence'] = $curtmdate;
                $presence = $this->presence->insertPresenceData($data_presence,'presence');
                $data_device['presence_id'] = $presence;
                $data_locate['presence_id'] = $presence;
                $data_loggng['presence_id'] = $presence;
            }else{
                $this->presence->updatePresenceData(array('end_attendence' => $curtmdate, 'status' => $status),$presence_info->id,'presence');
                $data_device['presence_id'] = $presence_info->id;
                $data_locate['presence_id'] = $presence_info->id;
                $data_loggng['presence_id'] = $presence_info->id;
            }
            
            $this->presence->insertPresenceData($data_device,'presence_device');     
            $this->presence->insertPresenceData($data_locate,'presence_location');   
            $this->presence->insertPresenceData($data_loggng,'presence_log');   
            return $this->response->setStatusCode(200)->setJSON([
                'status' => 'success',
                'message' => 'Data berhasil di kirim',
            ]); 
        }else{
            return $this->response->setStatusCode(200)->setJSON([
                'status' => 'warning',
                'message' => 'Terjadi kesalahan, harap coba kembali',
            ]); 
        }
    }

    public function storeFormIzin()
    {
        $sess   = session()->get();
        $lvcode = $this->request->getPost('opt_leave', FILTER_SANITIZE_STRING);
        $validationRule = [
            'attach' => [
                'label' => 'Document',
                'rules' => 'uploaded[attach]|mime_in[attach,application/pdf]|max_size[attach,1024]'
                ],
            'opt_leave' => 'required',
            'txt_reason' => 'required',
        ];

        if ($lvcode == 'LV07') {
            $validationRule += [
                'opt_leave_2' => 'required',
            ];
        }

        if (!$this->validate($validationRule)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $this->validator->getErrors()
            ]);
        }
    
        $file = $this->request->getFile('attach');
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/leave/', $newName);

            $fileType = $file->getClientMimeType(); 
            $fileSize = $file->getSize(); 
            $fileSizeKB = round($file->getSize() / 1024, 2);
            $fileSizeMB = round($file->getSize() / 1048576, 2);

            $fileData = array(
                'doc_key'       => $this->getKeydoc(),
                'username'      => $sess['username'],
                'leave_code'    => $lvcode,
                'date_start'    => date('Y-m-d',strtotime($this->request->getPost('txt_date_1', FILTER_SANITIZE_STRING))),
                'leave_note'    => $this->request->getPost('txt_reason', FILTER_SANITIZE_STRING),
                'status'        => 1,
                'file_name'     => $newName,
                'file_type'     => $fileType,
                'file_size'     => $fileSizeKB,
            );

            if ($lvcode == 'LV07') {
                $fileData['time_start'] = date('H:i',strtotime($this->request->getPost('txt_time', FILTER_SANITIZE_STRING)));
                $fileData['leave_init'] = $this->request->getPost('opt_leave_2', FILTER_SANITIZE_STRING);
            }

            if ($lvcode == 'LV08') {
                $fileData['time_end']   = date('H:i',strtotime($this->request->getPost('txt_time', FILTER_SANITIZE_STRING)));
                $fileData['leave_init'] = $this->request->getPost('opt_leave_2', FILTER_SANITIZE_STRING);
            }            
            

            if ($lvcode == 'LV10' || $lvcode == 'LV11' ) {
                $fileData['date_end'] = date('Y-m-d',strtotime($this->request->getPost('txt_date_2', FILTER_SANITIZE_STRING)));
            } 
            
            if ($lvcode == 'LV10') {
                $stockLeave =  $this->presence->CheckStockLeave($sess['username']);
                $date1      = $this->request->getPost('txt_date_1', FILTER_SANITIZE_STRING);
                $date2      = $this->request->getPost('txt_date_2', FILTER_SANITIZE_STRING);
                
                if (empty($date1) || empty($date2)) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Tanggal tidak boleh kosong!',
                    ]);
                }
                
                $date1Obj = new DateTime(date('Y-m-d', strtotime($date1)));
                $date2Obj = new DateTime(date('Y-m-d', strtotime($date2)));
                
                if ($date2Obj < $date1Obj) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Tanggal akhir harus lebih besar dari tanggal awal!',
                    ]);
                }
                
                $diff = $date1Obj->diff($date2Obj)->days;
                if ($stockLeave == 0) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Anda tidak memiliki stock cuti',
                    ]);
                } elseif ($stockLeave < $diff) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Stock cuti anda tidak mencukupi!',
                    ]);
                }                

            }

            $this->presence->insertPresenceData($fileData, 'presence_app');
            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Pengajuan Anda telah berhasil',
                'data'    => $fileData
            ]);
        }
        
        return $this->response->setJSON([
            'status' => false,
            'message' => 'File upload failed'
        ]);
    }

    public function presenceSetup(){
        $validationRules = [
            'set_presence_in'   => 'required',
            'set_presence_out'  => 'required'
        ];

        if (!$this->validate($validationRules)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => $this->validator->getErrors(),
            ]);
        }

        $this->presence->updateData(['setting_value' => $this->request->getPost('status_presence') ? 1 : 0],1,'presence_setting');
        $this->presence->updateData(array('setting_value' => date('H:i:s',strtotime($this->request->getPost('set_presence_in')))),2,'presence_setting');
        $this->presence->updateData(array('setting_value' => date('H:i:s',strtotime($this->request->getPost('set_presence_out')))),3,'presence_setting');

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Pengarutan presensi berhasil di perbaharui !'
        ]);
    }

    public function cancelFormIzin()
    {
        $key    = $this->request->getPost('key', FILTER_SANITIZE_STRING);
        $status = $this->request->getPost('status', FILTER_SANITIZE_STRING);
        $this->presence->updatePresenceData(array('status' => $status),$key,'presence_app');
        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Pengajuan Berhasil di batalkan',
        ]);
    }

    public function fetchDashboard()
    {
        $sess = session()->get();
        $user = $sess['username'];
        $resume = $this->presence->getResume($user);
        $leave  = $this->presence->CheckStockLeave($user);
        // $chart  = $this->presence->getHoursPresence($user);
        return $this->response->setJSON([
            'resume' => $resume,
            'leave'  => $leave,
        ]);
    }

    public function viewInstallment(){
        $sess = session()->get();
        $key  = $this->request->getPost('paramkey');
        $data = $this->presence->approvalInstallment($key);
        return $this->response->setJSON([
            'key'   => $key,
            'data'  => $data,
        ]);
    }

    public function fetchCalendarAgenda(){
        $sess = session()->get();
        $user = $sess['username'];
        $data  = $this->presence->getCalenderPresence($user);
        $events = [];
        foreach ($data as $row) {
            $events[] = [
                'id'    => $row['id'],
                'title' => $row['flag_mesg'],
                'start' => date('Y-m-d\TH:i:s', strtotime($row['presence_date'] . ' ' . $row['start_attend'])),
                'end'   => date('Y-m-d\TH:i:s', strtotime($row['presence_date'] . ' ' . $row['end_attend'])),
                'description' => $row['desc_attend'],
            ];
        }

        return $this->response->setJSON($events);
    }


    public function fetchdatapresence(){
        $sess       = session()->get();
        $username   = $sess['username'];
        $validpresence = $this->presence->checkDataPresence($username);

        $start_flag = 'T';
        $end_flag   = 'F';
        $start_time = null;
        $end_time   = null;

        if ($validpresence) {
            $presence   = $this->presence->getDataPresence($username);
            $start_flag =  (!$presence->start_attendence) ? 'T' : 'F';
            $end_flag   =  (!$presence->end_attendence) ? 'T' : 'F';
            $start_time = $presence->start_attendence;
            $end_time   = $presence->end_attendence;
        }

        $data = array(
            'avatar'    => $this->apps->getAvatar($sess['userid']),
            'name'      =>  'fetchdata',
            'start_flag'  => $start_flag,
            'end_flag'    => $end_flag,
            'start_time'  => $start_time != null ? date('H:i:s',strtotime($start_time)) : "-",
            'end_time'    => $end_time != null ?date('H:i:s',strtotime($end_time)) : "-",
        );
        return json_encode($data);
    }

    public function getOS() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $osArray = [
            'Windows'   => 'Windows',
            'Macintosh' => 'Mac OS',
            'Linux'     => 'Linux',
            'Ubuntu'    => 'Ubuntu',
            'iPhone'    => 'iOS',
            'Android'   => 'Android',
        ];
    
        foreach ($osArray as $os => $name) {
            if (stripos($userAgent, $os) !== false) {
                return $name;
            }
        }
    
        return 'Unknown OS';
    }
    
    public function getBrowser() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $browserArray = [
            'Edge'       => 'Edge',
            'Chrome'     => 'Chrome',
            'Safari'     => 'Safari',
            'Firefox'    => 'Firefox',
            'Opera'      => 'Opera',
            'MSIE'       => 'Internet Explorer',
            'Trident'    => 'Internet Explorer',
        ];
    
        foreach ($browserArray as $browser => $name) {
            if (stripos($userAgent, $browser) !== false) {
                return $name;
            }
        }

        return 'Unknown Browser';
    }

    public function updateProfile()
    {
        $key    = $this->request->getPost('txt_userid', FILTER_SANITIZE_STRING);
        $phone  = $this->request->getPost('txt_phone', FILTER_SANITIZE_STRING);
        $email  = $this->request->getPost('txt_email', FILTER_SANITIZE_STRING);
        $this->presence->updatePresenceData(array('phone' => $phone, 'email' => $email),$key,'data_pegawai');
        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Perubahan Berhasil di lakukan',
        ]);
    }

    public function getKeydoc()
    {
        $digits 	= 3;
        $sess       = session()->get();
        $username   = $sess['userid'];        
        $keydoc     = "LV".str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT)."-".$username.date("dmyis");	
        return $keydoc;
    }    

    public function historyPresent(){
        $data = $this->presence->getHistoryPresence();
        return $this->response->setJSON(['data' => $data]);
    }

    

}
