<?php

namespace App\Models;

use CodeIgniter\Model;

class ImportJobModel extends Model
{
    
    protected $table      = 'import_jobs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'file_path',
        'table_name',
        'total_rows',
        'processed_rows',
        'status',
        'last_line',
    ];

    public function createJob(array $payload, int $totalRows): int
    {
        $this->insert([
            'file_path'      => $payload['file'],
            'table_name'     => $payload['table'],
            'total_rows'     => $totalRows,
            'processed_rows' => 0,
            'status'         => 'running'
        ]);

        return $this->getInsertID();
    }

    public function getJobById(int $jobId): ?array
    {
        return $this->where('id', $jobId)->first();
    }

    public function increaseProcessedRows(int $jobId, int $count): bool
    {
        return (bool) $this->where('id', $jobId)
            ->set('processed_rows', "processed_rows + {$count}", false)
            ->update();
    }
    public function markDone(int $jobId): bool
    {
        return (bool) $this->where('id', $jobId)
        ->set('status', 'done')
        ->update();
    }
    public function insertBatchData(string $table, array $batch): void
    {
        $db = \Config\Database::connect();
        $db->table($table)->insertBatch($batch);
    }

}