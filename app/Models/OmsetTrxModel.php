<?php

namespace App\Models;

use CodeIgniter\Model;

class OmsetTrxModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
}
?>