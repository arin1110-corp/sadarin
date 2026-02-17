<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ModelPengumpulanBerkas;
use App\Services\GoogleDriveService;

class SyncBerkasCommand extends Command
{
    protected $signature = 'sync:berkas {jenis}';
    protected $description = 'Sinkronisasi berkas hanya untuk status=1 dan sync=0 per jenis kerja';

    public function handle(GoogleDriveService $googleDrive)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $jenis = strtolower($this->argument('jenis'));

        $mapJenis = [
            'pakta'                 => 'Pakta Integritas',
            'pakta_1_desember_2025' => 'Pakta Integritas 1 Desember 2025',
            'pakta_2025'            => 'Pakta Integritas',
            'model_c_2025'          => 'Model C 2025',
            'model_c_2026'          => 'Model C 2026',
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
        ];

        if (!isset($mapJenis[$jenis])) {
            $this->error("Jenis berkas tidak dikenal!");
            return Command::FAILURE;
        }

        $labelJenis = $mapJenis[$jenis];

        $this->info("Mulai sinkronisasi {$labelJenis}...");

        ModelPengumpulanBerkas::with('user')
            ->where('kumpulan_jenis', $labelJenis)
            ->where('kumpulan_status', 1)
            ->where('kumpulan_sync', 0)
            ->chunk(100, function ($records) use ($googleDrive, $jenis, $labelJenis) {

            foreach ($records as $record) {

                if (!$record->user) {
                    continue;
                }

                $user = $record->user;

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

                // Cek ke Google Drive
                $result = $googleDrive->findFileByNip(
                    $record->kumpulan_user,
                    $folderId,
                    $labelJenis
                );

                if ($result['status'] == 1) {

                    $record->update([
                        'kumpulan_file' => $result['file_url'],
                        'kumpulan_sync' => 1,
                    ]);

                    $this->info("{$record->kumpulan_user} → Sync selesai");
                }
                }
            });

        $this->info("Sinkronisasi selesai ✅");
        return Command::SUCCESS;
    }
}