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
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $jenis = strtolower($this->argument('jenis'));
        $this->info("Mulai sinkronisasi {$jenis}...");

        // Mapping jenis argumen ke nama berkas
        $mapJenis = [
            'pakta'                 => 'Pakta Integritas',
            'pakta_1_desember_2025' => 'Pakta Integritas 1 Desember 2025',
            'modelc'                => 'Model C 2025',
            'evkin_1'               => 'Evaluasi Kinerja Triwulan I',
            'evkin_2'               => 'Evaluasi Kinerja Triwulan II',
            'evkin_3'               => 'Evaluasi Kinerja Triwulan III',
            'evkin_4'               => 'Evaluasi Kinerja Triwulan IV',
            'evkin_tahunan'         => 'Evaluasi Kinerja Tahunan 2025',
            'umpan_1'               => 'Umpan Balik Triwulan I',
            'umpan_2'               => 'Umpan Balik Triwulan II',
            'umpan_3'               => 'Umpan Balik Triwulan III',
            'umpan_4'               => 'Umpan Balik Triwulan IV',
            'skp_2025'              => 'SKP 2025',
            'data_ktp'              => 'Data KTP',
            'data_npwp'             => 'Data NPWP',
            'data_buku_rekening'    => 'Data Buku Rekening',
            'data_bpjs_kesehatan'   => 'Data BPJS Kesehatan',
            'data_ijazah'           => 'Data Ijazah Terakhir',
            'data_kartu_keluarga'   => 'Data Kartu Keluarga',
            'modelc_2026'           => 'Model C 2026',
            'pakta_2025'             => 'Pakta Integritas',
            'laporanpjlpjanuari2025' => 'Laporan Bulanan PJLP Januari 2025',
            'coretax2026'            => 'Coretax 2026',
        ];

        if (!isset($mapJenis[$jenis])) {
            $this->error("Jenis berkas tidak dikenal!");
            return Command::FAILURE;
        }

        $users = ModelUser::all();

        foreach ($users as $user) {

            // ===============================
            // Tentukan identitas (NIP / NIK)
            // ===============================
            $identitas = ($user->user_nip && $user->user_nip !== '-')
                ? $user->user_nip
                : $user->user_nik;

            if (!$identitas) {
                $this->warn("User ID {$user->id} tidak punya NIP/NIK, dilewati");
                continue;
            }

            // ===============================
            // Tentukan ENV folder Google Drive
            // ===============================
            $folderMap = [
                1 => 'GOOGLE_DRIVE_FOLDER_PNS_',
                2 => 'GOOGLE_DRIVE_FOLDER_PPPK_',
                3 => 'GOOGLE_DRIVE_FOLDER_PARUHWAKTU_',
                4 => 'GOOGLE_DRIVE_FOLDER_NONASN_',
            ];

            if (!isset($folderMap[$user->user_jeniskerja])) {
                $this->warn("{$identitas} → Jenis kerja tidak valid");
                continue;
            }

            $envKey   = $folderMap[$user->user_jeniskerja] . strtoupper($jenis);
            $folderId = env($envKey);

            if (!$folderId) {
                $this->warn("ENV {$envKey} tidak ditemukan");
                continue;
            }

            // ===============================
            // Jeda request (hindari limit API)
            // ===============================
            usleep(500000); // 0.5 detik

            // ===============================
            // Cari file di Google Drive
            // ===============================
            $result = $googleDrive->findFileByNip(
                $identitas,
                $folderId,
                $mapJenis[$jenis]
            );

            // ===============================
            // Simpan / update database
            // ===============================
            ModelPengumpulanBerkas::updateOrCreate(
                [
                    'kumpulan_user'  => $identitas,
                    'kumpulan_jenis' => $mapJenis[$jenis],
                ],
                [
                    'kumpulan_file'   => $result['file_url'] ?? null,
                    'kumpulan_status' => $result['status'] ?? 0,
                ]
            );

            $this->info("{$identitas} → " . ($result['file_url'] ?? 'TIDAK ADA'));
        }

        $this->info("Sinkronisasi {$jenis} selesai!");
        return Command::SUCCESS;
    }
}