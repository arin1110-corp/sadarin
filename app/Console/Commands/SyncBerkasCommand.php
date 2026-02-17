<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ModelPengumpulanBerkas;
use App\Services\GoogleDriveService;

class SyncBerkasCommand extends Command
{
    protected $signature = 'sync:berkas {jenis}';
    protected $description = 'Sinkronisasi berkas + hapus file VPS jika sudah ada di Google Drive';

    public function handle(GoogleDriveService $googleDrive)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $jenis = strtolower($this->argument('jenis'));
        $this->info("Mulai sinkronisasi {$jenis}...");

        $mapJenis = [
            'pakta' => 'Pakta Integritas',
            'pakta_1_desember_2025' => 'Pakta Integritas 1 Desember 2025',
            'model_c_2025' => 'Model C 2025',
            'model_c_2026' => 'Model C 2026',
            'evkin_1' => 'Evaluasi Kinerja Triwulan I',
            'evkin_2' => 'Evaluasi Kinerja Triwulan II',
            'evkin_3' => 'Evaluasi Kinerja Triwulan III',
            'evkin_4' => 'Evaluasi Kinerja Triwulan IV',
            'evkin_tahunan' => 'Evaluasi Kinerja Tahunan 2025',
            'umpan_1' => 'Umpan Balik Triwulan I',
            'umpan_2' => 'Umpan Balik Triwulan II',
            'umpan_3' => 'Umpan Balik Triwulan III',
            'umpan_4' => 'Umpan Balik Triwulan IV',
            'skp_2025' => 'SKP 2025',
            'data_ktp' => 'Data KTP',
            'data_npwp' => 'Data NPWP',
            'data_buku_rekening' => 'Data Buku Rekening',
            'data_bpjs_kesehatan' => 'Data BPJS Kesehatan',
            'data_ijazah' => 'Data Ijazah Terakhir',
            'data_kartu_keluarga' => 'Data Kartu Keluarga',
            'pakta_2025' => 'Pakta Integritas',
        ];

        if (!isset($mapJenis[$jenis])) {
            $this->error('Jenis berkas tidak dikenal!');
            return Command::FAILURE;
        }

        ModelPengumpulanBerkas::query()
            ->select(
                'sadarin_pengumpulanberkas.*',
                'sadarin_user.user_jeniskerja'
            )
            ->leftJoin('sadarin_user', function ($join) {
            $join->on('sadarin_pengumpulanberkas.kumpulan_user', '=', 'sadarin_user.user_nip')
                ->orOn('sadarin_pengumpulanberkas.kumpulan_user', '=', 'sadarin_user.user_nik');
            })
            ->where('sadarin_pengumpulanberkas.kumpulan_jenis', $mapJenis[$jenis])
            ->where('sadarin_pengumpulanberkas.kumpulan_status', 1)
            ->where('sadarin_pengumpulanberkas.kumpulan_sync', 0)
            ->chunk(100, function ($rows) use ($googleDrive, $jenis, $mapJenis) {

            foreach ($rows as $row) {

                $identitas = $row->kumpulan_user;

                if (!$identitas || !$row->user_jeniskerja) {
                    continue;
                }

                $folderMap = [
                    1 => 'GOOGLE_DRIVE_FOLDER_PNS_',
                    2 => 'GOOGLE_DRIVE_FOLDER_PPPK_',
                    3 => 'GOOGLE_DRIVE_FOLDER_PARUHWAKTU_',
                    4 => 'GOOGLE_DRIVE_FOLDER_NONASN_',
                ];

                if (!isset($folderMap[$row->user_jeniskerja])) {
                    continue;
                }

                $envKey   = $folderMap[$row->user_jeniskerja] . strtoupper($jenis);
                $folderId = env($envKey);

                if (!$folderId) {
                    continue;
                }

                usleep(300000);

                $result = $googleDrive->findFileByNip(
                    $identitas,
                    $folderId,
                    $mapJenis[$jenis]
                );

                $oldFile = $row->kumpulan_file; // simpan file lama VPS

                if (($result['status'] ?? 0) == 1) {

                    // update hanya jika file ADA di Drive
                    $row->update([
                        'kumpulan_file'   => $result['file_url'],
                        'kumpulan_status' => 1,
                    ]);

                    if (!empty($oldFile)) {

                        $relativePath = parse_url($oldFile, PHP_URL_PATH);
                        $relativePath = ltrim($relativePath, '/');

                        $localPath = public_path($relativePath);

                        $this->warn("PATH CEK: " . $localPath);

                        if (file_exists($localPath)) {

                            if (unlink($localPath)) {
                                $this->info("{$identitas} → File VPS berhasil dihapus");
                            } else {
                                $this->warn("{$identitas} → Gagal hapus file VPS");
                            }
                        } else {
                            $this->warn("{$identitas} → File tidak ditemukan di VPS");
                        }
                    }

                    $row->update([
                        'kumpulan_sync' => 1
                    ]);
                } else {

                    // kalau TIDAK ada di Drive:
                    // jangan ubah kumpulan_file
                    $row->update([
                        'kumpulan_status' => 1
                    ]);
                }

                $this->info("{$identitas} → Sinkron selesai");
                }
            });

        $this->info("Sinkronisasi {$jenis} selesai!");
        return Command::SUCCESS;
    }
}