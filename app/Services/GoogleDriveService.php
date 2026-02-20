<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;

class GoogleDriveService
{
    protected $driveService;

    public function __construct()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/sadarin-drive.json'));
        $client->addScope(Drive::DRIVE_READONLY); // cukup readonly untuk cek file
        $this->driveService = new Drive($client);
    }

    public function findFileByNip($nip, $folderId, $jenis)
    {
        $driveService = $this->getClient($jenis);

        $query = sprintf("'%s' in parents and name contains '%s' and trashed = false", $folderId, $nip);

        $files = $driveService->files->listFiles([
            'q' => $query,
            'fields' => 'files(id, name, webViewLink)',
            'pageSize' => 1,
        ]);

        if (count($files->files) > 0) {
            $file = $files->files[0];
            return [
                'status' => 1,
                'file_url' => $file->webViewLink,
                'file_name' => $file->name,
            ];
        }

        return [
            'status' => 0,
            'file_url' => 'null',
            'file_name' => 'null',
        ];
    }
    private function getClient($jenis)
    {
        $client = new \Google_Client();
        $client->setScopes([\Google_Service_Drive::DRIVE_READONLY]);

        // mapping jenis → file json
        $jsonMap = [
            // JSON 1
            'data_ktp' => 'sadarin-kinerja.json',
            'data_npwp' => 'sadarin-kinerja.json',
            'data_bpjs_kesehatan' => 'sadarin-kinerja.json',
            'data_kartu_keluarga' => 'sadarin-kinerja.json',
            'data_buku_rekening' => 'sadarin-kinerja.json',
            'data_ijazah' => 'sadarin-kinerja.json',
            'model_c_2025' => 'sadarin-kinerja.json',
            'model_c_2026' => 'sadarin-kinerja.json',
            'evkin_1' => 'sadarin-kinerja.json',
            'evkin_2' => 'sadarin-kinerja.json',
            'evkin_3' => 'sadarin-kinerja.json',
            'evkin_4' => 'sadarin-kinerja.json',
            'evkin_tahunan' => 'sadarin-kinerja.json',
            'umpan_1' => 'sadarin-kinerja.json',
            'umpan_2' => 'sadarin-kinerja.json',
            'umpan_3' => 'sadarin-kinerja.json',
            'umpan_4' => 'sadarin-kinerja.json',
            'skp_2025' => 'sadarin-kinerja.json',
            'pakta_integritas' => 'sadarin-kinerja.json',
            'pakta_1_desember_2025' => 'sadarin-kinerja.json',

            // JSON 2
            'coretax_2026' => 'sadarin-kinerja-2.json',
            'laporan_ikd' => 'sadarin-kinerja-2.json',
            'laporan_pjlp_januari_2025' => 'sadarin-kinerja-2.json',
            'perjanjian_kinerja_2026' => 'sadarin-kinerja-2.json',
        ];

        if (!isset($jsonMap[$jenis])) {
            throw new \Exception("JSON credential untuk jenis {$jenis} tidak ditemukan.");
        }

        $client->setAuthConfig(storage_path('app/google/' . $jsonMap[$jenis]));

        return new \Google_Service_Drive($client);
    }
}