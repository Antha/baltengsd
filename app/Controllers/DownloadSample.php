<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DownloadSample extends Controller
{
    public function sampleTablePost()
    {
        $tableName = $this->request->getPost('table_name');

        if (!$tableName) {
            return redirect()->back()->with('error', 'Table tidak valid');
        }

        $db = \Config\Database::connect();

        if (!$db->tableExists($tableName)) {
            return redirect()->back()->with('error', 'Table tidak ditemukan');
        }

        // Ambil kolom
        $fields = $db->getFieldNames($tableName);

        // Ambil 10 data
        $data = $db->table($tableName)
                   ->limit(10)
                   ->get()
                   ->getResultArray();

        // Header CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="sample_'.$tableName.'.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // Header kolom
        fputcsv($output, $fields);

        // Data
        foreach ($data as $row) {
            $line = [];
            foreach ($fields as $field) {
                $line[] = $row[$field] ?? '';
            }
            fputcsv($output, $line);
        }

        fclose($output);
        exit;
    }
}
