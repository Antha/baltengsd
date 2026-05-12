<?php

namespace App\Controllers;
use App\Models\AuthModel;

class Auth extends BaseController
{
    public function index()
    {
        return view('auth_admin');
    }

    public function cekAjax()
    {
        //Rate Limiting/Throttling
        $ip = $this->request->getIPAddress();
        $key = 'verify_' . md5($ip);
        $limit = 5;
        $interval = 60; // detik

        $cache = cache(); // gunakan cache default (file, redis, dll)
        $data = $cache->get($key) ?? ['count' => 0, 'time' => time()];

        if ($data['count'] >= $limit && (time() - $data['time']) < $interval) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Terlalu banyak percobaan. Coba lagi dalam beberapa saat.'
            ]);
        }

        if ((time() - $data['time']) > $interval) {
            $data = ['count' => 1, 'time' => time()];
        } else {
            $data['count']++;
        }

        $cache->save($key, $data, $interval);


        //Verifikasi request
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => false, 'message' => 'Akses tidak valid']);
        }

        $rules = [
            'username' => [
                'label' => 'Username',
                'rules' => 'required|alpha_numeric',
                'errors' => [
                    'required' => 'Username wajib diisi.',
                    'alpha_numeric' => 'Username hanya boleh huruf dan angka.'
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password wajib diisi.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $this->validator->getErrors() // akan mengembalikan semua error
            ]);
        }

        $get_username = strtolower(trim($this->request->getPost('username'))); // Bersihkan input
        $get_password = trim($this->request->getPost('password'));

        $model = new AuthModel();
        $data = $model->where('username', $get_username)->first();//untuk mengambil username yang cocok

        if (password_verify($get_password, $data['password'])) {
            session()->set([
                'id' => $data['id'],
                'username' => $data['username'],
                'userType' => $data['level'],
                'isUser' => TRUE
            ]);

            $created_date = date('Y-m-d H:i:s');

            $model->insertLog($data['id'],$data['username'],$created_date);

            return $this->response->setJSON(['status' => true, 'redirect' => base_url('home')]);
        } else {
            return $this->response->setJSON(['status' => false, 'message' => 'Maaf, user tidak ditemukan']);
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('auth');
    }

}
?>