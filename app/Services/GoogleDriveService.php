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

        // Tentukan credential berdasarkan jenis berkas
        if (in_array(strtolower($jenis), ['pakta', 'foto'])) {
            $client->setAuthConfig(storage_path('app/google/sadarin-drive.json'));
        } else {
            $client->setAuthConfig(storage_path('app/google/sadarin-kinerja.json'));
        }

        return new \Google_Service_Drive($client);
    }
}
