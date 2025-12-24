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
}

?>