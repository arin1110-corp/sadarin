<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ModelUser;
use App\Models\ModelPengumpulanBerkas;
use App\Services\GoogleDriveService;

class SyncBerkasCommand extends Command
{
    protected $signature = 'sync:berkas';
    protected $description = 'Sinkronisasi berkas pegawai dengan Google Drive';

    public function handle(GoogleDriveService $googleDrive)
    {
        set_time_limit(0); // tanpa batas
        $this->info("Mulai sinkronisasi berkas...");

        $users = ModelUser::all();
        foreach ($users as $user) {
            $folderId = $user->user_jeniskerja == 1
                ? env('GOOGLE_DRIVE_FOLDER_PNS')
                : env('GOOGLE_DRIVE_FOLDER_PPPK');

            $result = $googleDrive->findFileByNip($user->user_nip, $folderId);

            ModelPengumpulanBerkas::updateOrCreate(
                [
                    'kumpulan_user' => $user->user_nip,
                    'kumpulan_jenis' => 'Pakta Integritas',
                ],
                [
                    'kumpulan_file'   => $result['file_url'],
                    'kumpulan_status' => $result['status'],
                ]
            );

            $this->info("{$user->user_nip} -> {$result['file_url']}");
        }

        $this->info("Sinkronisasi selesai!");
    }
}
