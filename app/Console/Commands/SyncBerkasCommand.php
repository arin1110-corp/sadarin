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
            'pakta'    => 'Pakta Integritas',
            'modelc'   => 'Model C 2025',
            'evkin_1'  => 'Evaluasi Kinerja Triwulan I',
            'evkin_2'  => 'Evaluasi Kinerja Triwulan II',
            'evkin_3'  => 'Evaluasi Kinerja Triwulan III',
            'umpan_1'  => 'Umpan Balik Triwulan I',
            'umpan_2'  => 'Umpan Balik Triwulan II',
            'umpan_3'  => 'Umpan Balik Triwulan III',
        ];

        if (!isset($mapJenis[$jenis])) {
            $this->error("Jenis berkas tidak dikenal!");
            return;
        }

        // ambil user cukup field penting saja, jauh lebih ringan
        $users = ModelUser::select('user_nip', 'user_jeniskerja')->get();
        $cacheFolder = [];

        foreach ($users as $user) {

            $envKey = $user->user_jeniskerja == 1
                ? 'GOOGLE_DRIVE_FOLDER_PNS_' . strtoupper($jenis)
                : 'GOOGLE_DRIVE_FOLDER_PPPK_' . strtoupper($jenis);

            $folderId = env($envKey);

            if (!$folderId) {
                $this->error("Folder ENV {$envKey} tidak ditemukan!");
                continue;
            }

            // PENTING: Panggil dengan dua parameter sesuai signature layanan
            if (!isset($cacheFolder[$folderId])) {
                $this->info("Ambil daftar file dari Drive untuk folder: {$folderId}");
                // perhatikan: kirim juga $jenis agar service memilih credential yang sesuai
                $cacheFolder[$folderId] = $googleDrive->getAllFilesInFolder($folderId, $jenis);
            }

            $files = $cacheFolder[$folderId];

            $found = collect($files)->first(function ($f) use ($user, $mapJenis, $jenis) {
                return str_contains(strtolower($f['name']), strtolower($user->user_nip))
                    && str_contains(strtolower($f['name']), strtolower($mapJenis[$jenis]));
            });

            ModelPengumpulanBerkas::updateOrCreate(
                [
                    'kumpulan_user'  => $user->user_nip,
                    'kumpulan_jenis' => $mapJenis[$jenis],
                ],
                [
                    'kumpulan_file'   => $found['url'] ?? null,
                    'kumpulan_status' => $found ? 1 : 0,
                ]
            );

            $this->info("{$user->user_nip} → " . ($found['url'] ?? 'TIDAK ADA'));
        }

        $this->info("Sinkronisasi {$jenis} selesai!");
    }
}