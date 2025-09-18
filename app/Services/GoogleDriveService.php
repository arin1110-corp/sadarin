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

    public function findFileByNip($nip, $folderId)
    {
        // Query cari file di folder sesuai NIP
        $query = sprintf("'%s' in parents and name contains '%s' and trashed = false", $folderId, $nip);

        $files = $this->driveService->files->listFiles([
            'q' => $query,
            'fields' => 'files(id, name, webViewLink)',
            'pageSize' => 1, // cukup ambil 1 file pertama
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
}
