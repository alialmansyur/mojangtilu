<?php

namespace App\Controllers\Auth;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use App\Models\Auth\AuthModel;
use App\Models\Auth\UserModel;
use App\Models\Apps\AppsModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends ResourceController
{
    use ResponseTrait; 

    public function index()
    {
        return view('Auth/login');
    }

    public function authprocess()
    {
        helper(['form']);
        $rules = [
            'o_userlogin' => 'required',
            'o_password' => 'required|min_length[8]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // $recaptchaResponse = $this->request->getPost('g-recaptcha-response');
        // if (!$this->validateRecaptcha($recaptchaResponse)) {
        //     return $this->failUnauthorized('reCAPTCHA verification failed');
        // }

        $username = filter_var($this->request->getPost('o_userlogin'), FILTER_SANITIZE_STRING);
        $password = $this->request->getPost('o_password');
        $model = new UserModel();
        $userdata = $model->where("username", $username)->first();

        if (!$userdata) {
            return $this->failNotFound('Username not found');
        }

        if (!password_verify($password, $userdata['password'])) {
            return $this->failValidationErrors('Wrong password');
        }

        $fingerprintJson = $this->request->getPost('fingerprint');
        if ($fingerprintJson) {
            $fingerprintData = json_decode($fingerprintJson, true);
            $ipAddress = $this->request->getIPAddress();
            $agentModel = new AppsModel();
            $userAgent = array(
                'username'      => $userdata['username'],
                'user_agent'    => $fingerprintData['user_agent'] ?? null,
                'user_agent_hash' => hash('sha256', $fingerprintData['user_agent'] ?? ''),
                'language'      => $fingerprintData['language'] ?? null,
                'platform'      => $fingerprintData['platform'] ?? null,
                'cpu_cores'     => $fingerprintData['cpu_cores'] ?? null,
                'device_memory' => $fingerprintData['device_memory'] ?? null,
                'screen_width'  => $fingerprintData['screen_width'] ?? null,
                'screen_height' => $fingerprintData['screen_height'] ?? null,
                'timezone'      => $fingerprintData['timezone'] ?? null,
                'touch_support' => $fingerprintData['touch_support'] ?? 0,
                'ip_address'    => $ipAddress,
                'created_at'    => date('Y-m-d H:i:s')
            );
            $agentModel->storeData($userAgent,'auth_useragent');
        }

        $key = getenv('JWT_TOKEN_SECRET');
        $loc = getenv('LOCATIONIQ_SECRET_KEY');
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'nbf' => $issuedAt,
            'user_id' => $userdata['id'],
            'user_name' => $userdata['username'],
            'user_fullname' => $userdata['fullname'],
        ];

        $tokenjwt = JWT::encode($payload, $key, 'HS256');
        session()->set('jwt_auth_token', $tokenjwt);
        return $this->respond([
            'status' => 'success',
            'message' => 'Login successful',
            'token' => $tokenjwt,
            'role'  => $userdata['role'],
            'locationiq' => $loc
        ]);
    }

    public function changePassword()
    {
        $model      = new UserModel();
        $password1 = htmlspecialchars($this->request->getPost('o_password1'));
        $password2 = htmlspecialchars($this->request->getPost('o_password2'));
        $password3 = htmlspecialchars($this->request->getPost('o_password3'));

        if ($password2 !== $password3) {
            return $this->failValidationErrors('Kata sandi tidak cocok.');
        }

        if ($password2 == $password1) {
            return $this->failValidationErrors('Kata sandi baru tidak boleh sama dengan yang lama.');
        }


        if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/', $password2)) {
            return $this->failValidationErrors('Kata sandi harus terdiri dari minimal 8 karakter, mengandung setidaknya satu huruf besar, satu huruf kecil, satu angka, dan satu simbol.');
        }


        $sess       = session()->get();
        $username   = $sess['username'];
        $sessID     = $sess['userid'];
        $userdata   = $model->where("username", $username)->first();

        if (!password_verify($password1, $userdata['password'])) {
            return $this->failUnauthorized('Password Lama anda tidak sesuai');
        }

        $hashedPassword = password_hash($password2, PASSWORD_BCRYPT);
        $model->updatePassword($sessID, $hashedPassword);
        return $this->respond([
            'status' => 'success',
            'messages' => 'Kata sandi berhasil diubah.'
        ]);
    }

    private function validateRecaptcha($recaptchaResponse)
    {
        $secretKey = getenv('RECAPTCHA_SECRET_KEY');
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
            'secret' => $secretKey,
            'response' => $recaptchaResponse
        ]));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        $response = curl_exec($curl);
        curl_close($curl);
        $responseData = json_decode($response);
        return isset($responseData->success) && $responseData->success;
    }

    public function logout()
    {
        session()->remove('jwt_auth_token');
        return redirect()->to('/login')->with('success', 'You have logged out successfully');
    }
}
