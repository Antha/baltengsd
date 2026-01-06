<?php

namespace App\Models;

use CodeIgniter\Model;

class ReplaceDataModel extends Model
{
    protected $allowedTables = [
        'db_outlet',
        'db_profile_outlet_m',
        'db_profile_outlet_m1',
        'db_sales_plan',
        'db_st_digipos',
        'db_st_nota'
    ];

    public function isAllowedTable(string $table): bool
    {
        return in_array($table, $this->allowedTables);
    }

    public function getTableColumns(string $table): array
    {
        return array_column(
            $this->db->getFieldData($table),
            'name'
        );
    }

    public function validateCSVHeader(array $csvHeader, array $tableColumns): array
    {
        $missing = array_diff($tableColumns, $csvHeader);
        $extra   = array_diff($csvHeader, $tableColumns);

        return [
            'valid'   => empty($missing) && empty($extra),
            'missing' => $missing,
            'extra'   => $extra
        ];
    }

    public function backupTable(string $table): string
    {
        $backupTable = $table . '_backup_' . date('Ymd_His');

        $this->db->query("CREATE TABLE {$backupTable} AS SELECT * FROM {$table}");

        return $backupTable;
    }

    public function replaceData(string $table, array $rows): bool
    {
        $this->db->transStart();
        $this->db->table($table)->truncate();
        $this->db->table($table)->insertBatch($rows);
        $this->db->transComplete();

        return $this->db->transStatus();
    }
}