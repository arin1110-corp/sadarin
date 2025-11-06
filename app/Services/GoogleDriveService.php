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
        $client->addScope(Drive::DRIVE_READONLY);
        $this->driveService = new Drive($client);
    }

    /**
     * Cari file berdasarkan NIP dalam folder
     * Auto retry jika error
     */
    public function findFileByNip($nip, $folderId)
    {
        $query = "'{$folderId}' in parents and name contains '{$nip}' and trashed = false";

        for ($i = 1; $i <= 3; $i++) {
            try {
                $files = $this->driveService->files->listFiles([
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

            } catch (\Exception $e) {
                // Jika error internal dari Google, tunggu dan ulangi
                sleep(2);
            }
        }

        // Jika tetap gagal setelah 3x
        return [
            'status' => 0,
            'file_url' => 'null',
            'file_name' => 'null',
        ];
    }
}