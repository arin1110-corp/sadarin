<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;

class GoogleDriveServiceDB
{
    public function findFileByNip($nip, $folderId, $jsonFile)
    {
        $driveService = $this->getClient($jsonFile);

        $query = sprintf(
            "'%s' in parents and name contains '%s' and trashed = false",
            $folderId,
            $nip
        );

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
            'file_url' => null,
            'file_name' => null,
        ];
    }

    private function getClient($jsonFile)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/' . $jsonFile));
        $client->addScope(Drive::DRIVE_READONLY);

        return new Drive($client);
    }
}