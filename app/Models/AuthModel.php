<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $table = 'db_users'; // Ganti dengan nama tabel sebenarnya
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id', 'username', 'password', 'level'
    ];

    public function insertLog(string $user_id, string $username, string $created_date): bool
    {
        $db = \Config\Database::connect();
        $builder = $db->table('db_logs');

        return $builder->insert([
            'user_id'      => $user_id,
            'user_name'     => $username,
            'created_date' => $created_date
        ]);
    }

}

?>