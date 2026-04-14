<?php

namespace App\Models;

use CodeIgniter\Model;

class ToolsModel extends Model
{
    protected $table      = 'db_telegram';
    protected $primaryKey = 'Id_Telegram';
    protected $allowedFields = ['Id_Telegram', 'role'];

    /**
     * Ambil role berdasarkan Id_Telegram
     *
     * @param string $idTelegram
     * @return string|null
     */
    public function getFilterByTelegramId(string $idTelegram)
    {
        $db = \Config\Database::connect();

        $sql = "SELECT role, sf_name as pic, tap 
                FROM db_telegram 
                WHERE Id_Telegram = ?";

        $result = $db->query($sql, [$idTelegram])->getRowArray();

        $role =  strtoupper($result['role']);

        switch ($role) {
            case "GM CLUSTER":
            case "MANAGER":
            case "MANAGER CLUSTER":
            case "MANAGER SUPPORT":
            case "BRANCH":
                $option = " AND 1 ";
                break;

            case "MANAGER TAP":
            //case "MANAGER TAP DPS":
            case "TL IDS":
            case "ADMIN SF":
            case "ADMIN IDS":
                $option = " AND TAP = '" . $result['tap'] . "' ";
                break;

            default:
                $option = " AND PIC = '" . $result['pic'] . "' ";
                break;
        }

        return $option;
    }

    public function getFilterTapByTelegramId(string $idTelegram)
    {
        $db = \Config\Database::connect();

        $sql = "SELECT role, sf_name as pic, tap 
                FROM db_telegram 
                WHERE Id_Telegram = ?";

        $result = $db->query($sql, [$idTelegram])->getRowArray();

        $role =  strtoupper($result['role']);

        switch ($role) {
            case "GM CLUSTER":
            case "MANAGER":
            case "MANAGER CLUSTER":
            case "MANAGER SUPPORT":
            case "ADMIN IDS":
            case "BRANCH":
                $option = " AND 1 ";
                break;

            case "MANAGER TAP":
            //case "MANAGER TAP DPS":
            case "TL IDS":
            case "ADMIN SF":
                $option = " AND TAP = '" . $result['tap'] . "' ";
                break;

            default:
                $option = " AND TAP = '" . $result['tap'] . "' ";
                break;
        }

        return $option;
    }
}