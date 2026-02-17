<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ModelPengumpulanBerkas;
use App\Services\GoogleDriveService;

class SyncBerkasCommand extends Command
{
    protected $signature = 'sync:berkas {jenis}';
    protected $description = 'Sinkronisasi berkas hanya untuk yang status=1 dan sync=0';

    public function handle(GoogleDriveService $googleDrive)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $jenis = strtolower($this->argument('jenis'));

        $mapJenis = [
            'pakta'                 => 'Pakta Integritas',
            'pakta_1_desember_2025' => 'Pakta Integritas 1 Desember 2025',
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
            'pakta_2025'            => 'Pakta Integritas',
        ];

        if (!isset($mapJenis[$jenis])) {
            $this->error("Jenis berkas tidak dikenal!");
            return Command::FAILURE;
        }

        $labelJenis = $mapJenis[$jenis];

        $this->info("Mulai sinkronisasi {$labelJenis}...");

        ModelPengumpulanBerkas::where('kumpulan_jenis', $labelJenis)
            ->where('kumpulan_status', 1)
            ->where('kumpulan_sync', 0)
            ->chunk(100, function ($records) use ($googleDrive, $jenis, $labelJenis) {

            foreach ($records as $record) {

                $identitas = $record->kumpulan_user;

                // Ambil folder dari env
                $folderKey = 'GOOGLE_DRIVE_FOLDER_' . strtoupper($jenis);
                $folderId  = env($folderKey);

                if (!$folderId) {
                    continue;
                }

                // Cek ulang ke Drive
                $result = $googleDrive->findFileByNip(
                    $identitas,
                    $folderId,
                    $labelJenis
                );

                if ($result['status'] == 1) {

                    // Update link jika perlu
                    $record->update([
                        'kumpulan_file' => $result['file_url'],
                        'kumpulan_sync' => 1,
                    ]);

                    $this->info("{$identitas} → Sync selesai");
                }
                }
            });

        $this->info("Sinkronisasi selesai ✅");
        return Command::SUCCESS;
    }
}