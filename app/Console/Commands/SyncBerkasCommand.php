<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ModelUser;
use App\Models\ModelPengumpulanBerkas;
use App\Services\GoogleDriveService;

class SyncBerkasCommand extends Command
{
    protected $signature = 'sync:berkas {jenis}';
    protected $description = 'Sinkronisasi berkas pegawai dengan Google Drive';

    public function handle(GoogleDriveService $googleDrive)
    {
        set_time_limit(0);

        $jenis = strtolower($this->argument('jenis'));
        $this->info("Mulai sinkronisasi {$jenis}...");

        $mapJenis = [
            'pakta'  => 'Pakta Integritas',
            'modelc' => 'Model C 2025',
            'evkin'  => 'Evaluasi Kinerja',
            'umbal'  => 'Umpan Balik',
        ];

        if (!isset($mapJenis[$jenis])) {
            $this->error("Jenis berkas tidak dikenal!");
            return;
        }

        $users = ModelUser::all();

        foreach ($users as $user) {
            // tentukan key env sesuai jenis dan jeniskerja
            $envKey = $user->user_jeniskerja == 1
                ? 'GOOGLE_DRIVE_FOLDER_PNS_' . strtoupper($jenis)
                : 'GOOGLE_DRIVE_FOLDER_PPPK_' . strtoupper($jenis);

            $folderId = env($envKey);

            $result = $googleDrive->findFileByNip($user->user_nip, $folderId, $mapJenis[$jenis]);

            ModelPengumpulanBerkas::updateOrCreate(
                [
                    'kumpulan_user' => $user->user_nip,
                    'kumpulan_jenis' => $mapJenis[$jenis],
                ],
                [
                    'kumpulan_file'   => $result['file_url'],
                    'kumpulan_status' => $result['status'],
                ]
            );

            $this->info("{$user->user_nip} -> {$result['file_url']}");
        }
        $this->info("FolderID = " . $folderId);
        $this->info("Sinkronisasi {$jenis} selesai!");
    }
}
