<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\TelegramAuthModel;
use App\Models\TelegramDataModel;

class Telegram extends BaseController
{
    protected $cache;

    public function __construct()
    {
        $this->cache = \Config\Services::cache();
    }

    public function verify()
    {
        $user_id = $this->request->getPost('parse_idtel');

        if (!$user_id) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'telegram_id is required'
            ])->setStatusCode(400);
        }

        /* ===============================
         * RATE LIMIT (1 user / 5 detik)
         * =============================== */
        $rateKey = 'tg_rate_' . $user_id;

        if ($this->cache->get($rateKey)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Too many requests'
            ])->setStatusCode(429);
        }

        // set rate limit 5 detik
        $this->cache->save($rateKey, true, 5);

        /* ===============================
         * CACHE RESULT (10 menit)
         * =============================== */
        $cacheKey = 'tg_verify_' . $user_id;
        $cached   = $this->cache->get($cacheKey);

        if ($cached !== null) {
            return $this->response->setJSON($cached);
        }

        /* ===============================
         * DB CHECK
         * =============================== */

        $model = new TelegramAuthModel();
        $user = $model->query_verify_user_telegram($user_id);

        if ($user) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Telegram user verified',
                'telegram_id' => $user['Id_Telegram']
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Telegram user not registered'
            ])->setStatusCode(404);
        }
    }

    public function sf_list()
    {
        /* ===============================
         * Get User List
         * =============================== */

        $model = new TelegramDataModel();

        //$idtel = '1163748435';//hapus & ganti dengan data idtsel dari user yg mengakses modul
        $idtel = $this->request->getGet('parse_idtel');

        $sf_list = $model->query_sf_list($idtel);

        if (!empty($sf_list)) {
            return $this->response ->setStatusCode(200)
            ->setJSON([
                'status' => true,
                'message' => 'SF List Available',
                'sf_list' => $sf_list
            ]);
        } else {
            return $this->response
            ->setStatusCode(404)
            ->setJSON([
                'status'  => false,
                'message' => 'SF List not available',
                'sf_list' => []
            ]);
        }
    }
}
