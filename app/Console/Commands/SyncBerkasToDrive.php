<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ModelPengumpulanBerkas;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class SyncBerkasToDrive extends Command
{
    protected $signature = 'sync:berkas-drive';
    protected $description = 'Sync file VPS ke Google Drive';

    public function handle()
    {
        $this->info('Mulai proses sync...');

        $data = ModelPengumpulanBerkas::where('kumpulan_status', 0)->get();

        if ($data->isEmpty()) {
            $this->info('Tidak ada file untuk disync.');
            return;
        }

        // ===== GOOGLE CLIENT =====
        $client = new Client();
        $client->setAuthConfig(storage_path(env('GOOGLE_APPLICATION_CREDENTIALS')));
        $client->addScope(Drive::DRIVE);

        $driveService = new Drive($client);

        foreach ($data as $item) {

            $fullPath = public_path($item->kumpulan_file);

            if (!file_exists($fullPath)) {
                $this->error("File tidak ditemukan: {$item->kumpulan_file}");
                $item->update(['kumpulan_status' => 2]);
                continue;
            }

            try {

                $fileMetadata = new DriveFile([
                    'name' => basename($fullPath),
                    'parents' => [env('GOOGLE_DRIVE_FOLDER_ID')],
                ]);

                $uploadedFile = $driveService->files->create($fileMetadata, [
                    'data' => file_get_contents($fullPath),
                    'mimeType' => 'application/pdf',
                    'uploadType' => 'multipart',
                    'fields' => 'id',
                    'supportsAllDrives' => true,
                ]);

                $fileId = $uploadedFile->getId();

                $driveLink = "https://drive.google.com/file/d/{$fileId}/view";

                // Update DB
                $item->update([
                    'kumpulan_file' => $driveLink,
                    'kumpulan_status' => 1,
                ]);

                // Hapus file VPS
                if (str_contains($item->kumpulan_file, 'assets/')) {
                    unlink($fullPath);
                }

                $this->info("Berhasil sync: {$driveLink}");

            } catch (\Exception $e) {

                $item->update(['kumpulan_status' => 2]);
                $this->error("Gagal upload: " . $e->getMessage());
            }
        }

        $this->info('Proses selesai.');
    }
}