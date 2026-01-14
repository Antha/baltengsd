<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\TelegramAuthModel;

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
}
