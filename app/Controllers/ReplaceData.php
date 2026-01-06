<?php

namespace App\Controllers;

use App\Models\ReplaceDataModel;

class ReplaceData extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new ReplaceDataModel();
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

        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            $rows[] = array_combine($header, $data);
        }
        fclose($handle);

        session()->set('replace_payload', [
            'table' => $table,
            'rows'  => $rows
        ]);

        return view('replace_preview', [
            'header' => $header,
            'rows'   => array_slice($rows, 0, 10) // preview first 10 rows
        ]);
    }

    public function confirm()
    {
        $payload = session()->get('replace_payload');

        if (!$payload) {
            return redirect()->to('replace/upload');
        }

        $backupTable = $this->model->backupTable($payload['table']);
        $this->model->replaceData($payload['table'], $payload['rows']);

        session()->remove('replace_payload');

        return redirect()->to('replace/upload')
            ->with('success', "Data replaced successfully. Backup: {$backupTable}");
    }
}