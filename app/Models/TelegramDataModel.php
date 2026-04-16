<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\ToolsModel;

class TelegramDataModel extends Model
{
    protected $table = 'db_telegram';
    protected $returnType = 'array';

    public function query_sf_list($idtel)
    {
        $toolsModel = new ToolsModel();
        $optionFilter = $toolsModel->getFilterByTelegramId($idtel);

        $query = "SELECT sf_name FROM db_telegram WHERE `role` IN('SF','CANVASSER') $optionFilter";

        return $this->db->query($query)->getResultArray();
    }

    public function query_sf_list_telegram($idtel)
    {
        $toolsModel = new ToolsModel();
        $optionFilter = $toolsModel->getFilterByTelegramId($idtel);
        $query = "SELECT distinct pic as sf_name FROM db_outlet WHERE channel = 'SF CHANNELING' $optionFilter"; 

        return $this->db->query($query)->getResultArray();
    }
}

?>