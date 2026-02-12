<?php

namespace App\Services;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_Permission;

class GoogleDriveService
{
    protected $credentials = [
        'sadarin-drive.json',
        'sadarin-kinerja.json',
        // tambahkan lagi kalau penuh
    ];

    protected function getClient($credentialFile)
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path("app/google/{$credentialFile}"));
        $client->setScopes([Google_Service_Drive::DRIVE]);

        return new Google_Service_Drive($client);
    }

    public function uploadFileWithFailover($filePath, $folderId)
    {
        foreach ($this->credentials as $credential) {

            try {

                $driveService = $this->getClient($credential);

                $fileMetadata = new Google_Service_Drive_DriveFile([
                    'name' => basename($filePath),
                    'parents' => [$folderId],
                ]);

                $content = file_get_contents($filePath);

                $file = $driveService->files->create($fileMetadata, [
                    'data' => $content,
                    'mimeType' => mime_content_type($filePath),
                    'uploadType' => 'multipart',
                    'fields' => 'id, webViewLink'
                ]);

                // Set public permission
                $permission = new Google_Service_Drive_Permission([
                    'type' => 'anyone',
                    'role' => 'reader',
                ]);

                $driveService->permissions->create($file->id, $permission);

                return $file->webViewLink;
            } catch (\Exception $e) {
                // kalau gagal coba credential berikutnya
                continue;
            }
        }

        return null;
    }
}