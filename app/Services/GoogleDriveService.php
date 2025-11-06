<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;

class GoogleDriveService
{
    protected $clients = [];

    public function __construct()
    {
        // Kosong, karena client akan dipilih sesuai jenis
    }

    /**
     * Ambil client berdasarkan jenis berkas
     */
    private function getDriveByJenis($jenis)
    {
        $jenis = strtolower($jenis);

        // Cache biar tidak buat client berulang
        if (isset($this->clients[$jenis])) {
            return $this->clients[$jenis];
        }

        $client = new Client();
        $client->setScopes([Drive::DRIVE_READONLY]);

        if (in_array($jenis, ['pakta', 'foto'])) {
            $client->setAuthConfig(storage_path('app/google/sadarin-drive.json'));
        } else {
            $client->setAuthConfig(storage_path('app/google/sadarin-kinerja.json'));
        }

        $this->clients[$jenis] = new Drive($client);
        return $this->clients[$jenis];
    }

    /**
     * AMBIL SEMUA FILE DALAM 1 FOLDER (PAGINATION)
     * — aman untuk ribuan file, tidak putus di tengah —
     */
    public function getAllFilesInFolder($folderId, $jenis)
    {
        $drive = $this->getDriveByJenis($jenis);

        $files = [];
        $pageToken = null;

        do {
            try {
                $response = $drive->files->listFiles([
                    'q' => "'{$folderId}' in parents and trashed = false",
                    'fields' => 'nextPageToken, files(id, name, webViewLink)',
                    'pageSize' => 200,
                    'supportsAllDrives' => true,
                    'includeItemsFromAllDrives' => true,
                    'pageToken' => $pageToken
                ]);

                foreach ($response->files as $f) {
                    $files[] = [
                        'id' => $f->id,
                        'name' => $f->name,
                        'url' => $f->webViewLink
                    ];
                }

                $pageToken = $response->nextPageToken;
                usleep(200000); // jeda 0.2 detik agar tidak kena rate limit

            } catch (\Exception $e) {
                \Log::error("Google API Error: " . $e->getMessage());
                sleep(2); // retry
            }
        } while ($pageToken);

        return $files;
    }
}