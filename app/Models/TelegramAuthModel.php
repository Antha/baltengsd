<?php

namespace App\Models;

use CodeIgniter\Model;

class TelegramAuthModel extends Model
{
    protected $table = 'db_telegram';

    public function query_verify_user_telegram($user_id)
    {
        return $this->where('Id_Telegram', $user_id)->first();
    }
}

?>