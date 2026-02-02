<?php

namespace App\Controllers;

use App\Models\ImportJobModel;
use App\Models\ReplaceDataModel;

class ReplaceData extends BaseController
{
    protected $model;
    protected $importModel;

    public function __construct()
    {
        $this->model = new ReplaceDataModel();
        $this->importModel = new ImportJobModel();
    }

    public function upload()
    {
        return view('replace_upload');
    }

    public function preview()
    {
        $table = $this->request->getPost('table_name');
        $file  = $this->request->getFile('csv_file');

        if (!$file->isValid() || $file->getExtension() !== 'csv') {
            return redirect()->back()->with('error', 'Invalid CSV file');
        }

        $handle = fopen($file->getTempName(), 'r');
        $header = fgetcsv($handle);

        $tableColumns = $this->model->getTableColumns($table);
        $validation   = $this->model->validateCSVHeader($header, $tableColumns);

        if (!$validation['valid']) {
            return redirect()->back()->with(
                'error',
                'Column mismatch. Missing: '
                . implode(',', $validation['missing'])
                . ' | Extra: '
                . implode(',', $validation['extra'])
            );
        }

        $previewRows = [];
        $count = 0;
        while (($data = fgetcsv($handle)) !== false && $count < 10) {
            $previewRows[] = array_combine($header, $data);
            $count++;
        }
        fclose($handle);

        // ✅ PINDAHKAN FILE KE LOKASI PERMANEN
        $uploadPath = WRITEPATH . 'uploads/csv/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = 'replace_' . time() . '.csv';
        $file->move($uploadPath, $newName);
        $csvPath = $uploadPath . $newName;

        // ✅ SIMPAN PATH FILE, BUKAN TEMP NAME
        session()->set('replace_payload', [
            'table' => $table,
            'file'  => $csvPath
        ]);

        return view('replace_preview', [
            'header' => $header,
            'rows'   => $previewRows
        ]);

        return view('replace_preview', [
            'header' => $header,
            'rows'   => $previewRows // preview first 10 rows
        ]);
    }

    public function confirm_old()
    {
        $payload = session()->get('replace_payload');

        if (!$payload) {
            return redirect()->to('replace/upload');
        }

        $table = $payload['table'];
        $file  = $payload['file'];

        if (!file_exists($file)) {
            return redirect()->to('replace/upload')
                ->with('error', 'File CSV tidak ditemukan');
        }

        $this->model->backupTable($table);
        $this->model->truncateTable($table);

        $handle = fopen($file, 'r');
        $header = fgetcsv($handle);

        $batch = [];
        $limit = 1000;

        while (($data = fgetcsv($handle)) !== false) {
            $batch[] = array_combine($header, $data);

            if (count($batch) === $limit) {
                $this->model->insertBatchDynamic($table, $batch);
                $batch = []; // 💣 lepas memory
            }
        }

        if (!empty($batch)) {
            $this->model->insertBatchDynamic($table, $batch);
        }

        fclose($handle);
        unlink($file); // optional tapi disarankan
        session()->remove('replace_payload');

        return redirect()->to('replace/upload')
            ->with('success', 'Data replaced successfully');
    }

    public function confirm()
    {
        $payload = session()->get('replace_payload');

        if (!$payload) {
            return redirect()->to('replace/upload');
        }

        $table = $payload['table'];
        $file  = $payload['file'];

        if (!file_exists($file)) {
            return redirect()->to('replace/upload')
                ->with('error', 'File CSV tidak ditemukan');
        }

        // 🔥 Trigger CLI import (BACKGROUND)
        $cmd = sprintf(
            'php %sspark import:csv %s %s > /dev/null 2>&1 &',
            ROOTPATH,
            escapeshellarg($file),
            escapeshellarg($table)
        );

        exec($cmd);

        session()->remove('replace_payload');

        return redirect()->to('replace/upload')
            ->with('success', 'Import sedang diproses di background. Silakan tunggu.');
    }

    public function startImport()
    {
        $payload = session()->get('replace_payload');
        if (!$payload) {
            return redirect()->to('replace/upload')
            ->with('error', 'Session import tidak ditemukan');
        }

        $this->model->truncateTable($payload['table']);

        // hitung total baris (sekali saja)
        $total = 0;
        $handle = fopen($payload['file'], 'r');
        fgetcsv($handle);
        while (fgetcsv($handle)) $total++;
        fclose($handle);

        $jobId = $this->importModel->createJob($payload,$total);

        return view('replace_progress', ['jobId' => $jobId]);
    }

    public function processImport($jobId)
    {
        //$job = $this->model->table('import_jobs')->where('id', $jobId)->get()->getRowArray();
        $job = $this->importModel->getJobById($jobId);

        if (!$job) {
            return $this->response->setJSON([
                'done' => true,
                'error' => 'Job tidak ditemukan'
            ]);
        }

        if ($job['status'] === 'done') {
            return $this->response->setJSON([
                'done'      => true,
                'processed' => (int)$job['total_rows'],
                'total'     => (int)$job['total_rows']
            ]);
        }

        $limit = 1000;
        $count  = 0;
        $batch  = [];

        $handle = fopen($job['file_path'], 'r');
        $header = fgetcsv($handle);

        // 🔥 SKIP BARIS YANG SUDAH DIPROSES (RESUME)
        for ($i = 0; $i < $job['processed_rows']; $i++) {
            fgetcsv($handle);
        }

        //$batch = [];
        //$count = 0;
        while (($row = fgetcsv($handle)) !== false && $count < $limit) {
            $batch[] = array_combine($header, $row);
            $count++;
        }
        $isEOF = feof($handle);
        fclose($handle);

        if ($batch) {
            $this->importModel->insertBatchData($job['table_name'],$batch);
            $this->importModel->increaseProcessedRows($jobId, $count);
        }

        // ✅ CEK SELESAI BERDASARKAN TOTAL
        if ((int)$job['processed_rows'] >= (int)$job['total_rows']) {
            $this->importModel->markDone($jobId);

            return $this->response->setJSON([
                'done'      => true,
                'processed' => (int)$job['total_rows'],
                'total'     => (int)$job['total_rows']
            ]);
        }


        // 🔁 MASIH LANJUT
        return $this->response->setJSON([
            'done'      => false,
            'processed' => (int)$job['processed_rows'] + $count,
            'total'     => (int)$job['total_rows']
        ]);

    }

}