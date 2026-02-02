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
        'db_st_nota',
        'import_jobs'
    ];

    public function isAllowedTable(string $table): bool
    {
        return in_array($table, $this->allowedTables, true);
    }

    protected function assertAllowed(string $table): void
    {
        if (!$this->isAllowedTable($table)) {
            throw new \InvalidArgumentException('Table not allowed');
        }
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
        if (!$this->isAllowedTable($table)) {
            throw new \InvalidArgumentException('Table not allowed');
        }

        $backupTable = $table . '_backup_' . date('Ymd_His');

        $this->db->query("CREATE TABLE {$backupTable} LIKE {$table}");
        $this->db->query("INSERT INTO {$backupTable} SELECT * FROM {$table}");

        return $backupTable;
    }

    public function insertBatchDynamic(string $table, array $data)
    {
        $this->assertAllowed($table);
        
        if (empty($data)) {
            return false;
        }

        return $this->db
            ->table($table)
            ->insertBatch($data);
    }

    public function truncateTable(string $table)
    {
        $this->assertAllowed($table);

        return $this->db->table($table)->truncate();
    }

}