<?php

namespace App\Models;

use CodeIgniter\Model;

class TelegramDataModel extends Model
{
    protected $table = 'db_telegram';
    protected $returnType = 'array';

    public function query_sf_list()
    {
        return $this->select('sf_name')->where('role', 'SF')->findAll();
    }
}

?>