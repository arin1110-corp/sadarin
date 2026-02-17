<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ModelUser;
use App\Models\ModelPengumpulanBerkas;
use App\Services\GoogleDriveService;

class SyncBerkasCommand extends Command
{
    protected $signature = 'sync:berkas {jenis}';
    protected $description = 'Sinkronisasi berkas + hapus file lokal jika sudah ada di Google Drive';

    public function handle(GoogleDriveService $googleDrive)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $jenis = strtolower($this->argument('jenis'));
        $this->info("Mulai sinkronisasi {$jenis}...");

        $mapJenis = [
            'pakta'                 => 'Pakta Integritas',
            'pakta_1_desember_2025' => 'Pakta Integritas 1 Desember 2025',
            'model_c_2025'                => 'Model C 2025',
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
            'model_c_2026'           => 'Model C 2026',
            'pakta_2025'            => 'Pakta Integritas',
        ];

        if (!isset($mapJenis[$jenis])) {
            $this->error("Jenis berkas tidak dikenal!");
            return Command::FAILURE;
        }

        ModelUser::chunk(100, function ($users) use ($googleDrive, $jenis, $mapJenis) {

            foreach ($users as $user) {

                // ==============================
                // Ambil NIP / NIK
                // ==============================
                $identitas = ($user->user_nip && $user->user_nip !== '-')
                    ? $user->user_nip
                    : $user->user_nik;

                if (!$identitas) {
                    continue;
                }

                // ==============================
                // Tentukan folder berdasarkan jenis kerja
                // ==============================
                $folderMap = [
                    1 => 'GOOGLE_DRIVE_FOLDER_PNS_',
                    2 => 'GOOGLE_DRIVE_FOLDER_PPPK_',
                    3 => 'GOOGLE_DRIVE_FOLDER_PARUHWAKTU_',
                    4 => 'GOOGLE_DRIVE_FOLDER_NONASN_',
                ];

                if (!isset($folderMap[$user->user_jeniskerja])) {
                    continue;
                }

                $envKey   = $folderMap[$user->user_jeniskerja] . strtoupper($jenis);
                $folderId = env($envKey);

                if (!$folderId) {
                    continue;
                }

                // Jeda kecil biar aman dari limit API
                usleep(300000);

                // ==============================
                // Cek file di Google Drive
                // ==============================
                $result = $googleDrive->findFileByNip(
                    $identitas,
                    $folderId,
                    $mapJenis[$jenis]
                );

                // ==============================
                // Update atau create record
                // ==============================
                $record = ModelPengumpulanBerkas::updateOrCreate(
                    [
                        'kumpulan_user'  => $identitas,
                        'kumpulan_jenis' => $mapJenis[$jenis],
                    ],
                    [
                        'kumpulan_file'   => $result['file_url'] ?? null,
                        'kumpulan_status' => $result['status'] ?? 0,
                    ]
                );

                // ==============================
                // HAPUS FILE LOKAL JIKA SUDAH SYNC
                // ==============================
                if (
                    $record->kumpulan_status == 1 &&
                    $record->kumpulan_sync == 0
                ) {

                    if (!empty($record->kumpulan_file)) {

                        $localPath = storage_path($record->kumpulan_file);

                        if (file_exists($localPath)) {

                            unlink($localPath);

                            $this->info("{$identitas} → File lokal dihapus");
                        } else {
                            $this->warn("{$identitas} → File lokal tidak ditemukan");
                        }
                    }

                    // Set sync jadi 1
                    $record->update([
                        'kumpulan_sync' => 1
                    ]);
                }

                $this->info("{$identitas} → Sinkron selesai");
            }
        });

        $this->info("Sinkronisasi {$jenis} selesai!");
        return Command::SUCCESS;
    }
}