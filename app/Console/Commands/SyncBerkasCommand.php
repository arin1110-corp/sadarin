<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ModelPengumpulanBerkas;
use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\DB;

class SyncBerkasCommand extends Command
{
    protected $signature = 'sync:berkas {jenis}';
    protected $description = 'Sinkronisasi berkas ke Google Drive';

    public function handle(GoogleDriveService $googleDrive)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $jenis = strtolower($this->argument('jenis'));
        $this->info("Mulai sinkronisasi {$jenis}...");

        $mapJenis = [
            'pakta_integritas' => 'Pakta Integritas',
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
            'coretax_2026' => 'Coretax 2026',
            'laporan_pjlp_januari_2025' => 'Laporan PJLP Januari 2025',
            'laporan_ikd' => 'Laporan IKD',
            'perjanjian_kinerja_2026' => 'Perjanjian Kinerja 2026',
        ];

        if (!isset($mapJenis[$jenis])) {
            $this->error('Jenis berkas tidak dikenal!');
            return Command::FAILURE;
        }

        $total = ModelPengumpulanBerkas::where('kumpulan_jenis', $mapJenis[$jenis])
            ->where('kumpulan_status', 1)
            ->where('kumpulan_sync', 0)
            ->count();

        $this->info("Total akan diproses: {$total}");

        ModelPengumpulanBerkas::query()
            ->select(
                'sadarin_pengumpulanberkas.*',
            DB::raw('COALESCE(u1.user_jeniskerja, u2.user_jeniskerja) as user_jeniskerja')
            )
            ->leftJoin('sadarin_user as u1', 'sadarin_pengumpulanberkas.kumpulan_user', '=', 'u1.user_nip')
            ->leftJoin('sadarin_user as u2', 'sadarin_pengumpulanberkas.kumpulan_user', '=', 'u2.user_nik')
            ->where('sadarin_pengumpulanberkas.kumpulan_jenis', $mapJenis[$jenis])
            ->where('sadarin_pengumpulanberkas.kumpulan_status', 1)
            ->where(function ($q) {
                $q->where('sadarin_pengumpulanberkas.kumpulan_sync', 0)
                    ->orWhereNull('sadarin_pengumpulanberkas.kumpulan_sync');
            })
            ->chunk(100, function ($rows) use ($googleDrive, $jenis) {

                $this->info("Proses chunk: " . $rows->count());

            foreach ($rows as $row) {

                $identitas = trim($row->kumpulan_user);

                if (!$identitas) {
                    $this->warn("SKIP: identitas kosong");
                    continue;
                }

                if (!$row->user_jeniskerja) {
                    $this->error("USER TIDAK DITEMUKAN: {$identitas}");
                    continue;
                }

                $folderMap = [
                    1 => 'GOOGLE_DRIVE_FOLDER_PNS_',
                    2 => 'GOOGLE_DRIVE_FOLDER_PPPK_',
                    3 => 'GOOGLE_DRIVE_FOLDER_PARUHWAKTU_',
                    4 => 'GOOGLE_DRIVE_FOLDER_NONASN_',
                ];

                if (!isset($folderMap[$row->user_jeniskerja])) {
                    $this->warn("Jenis kerja tidak valid: {$identitas}");
                    continue;
                }

                $envKey   = $folderMap[$row->user_jeniskerja] . strtoupper($jenis);
                $folderId = env($envKey);

                if (!$folderId) {
                    $this->error("ENV tidak ditemukan: {$envKey}");
                    continue;
                }

                try {
                    $this->info("CEK DRIVE: {$identitas}");

                    $result = $googleDrive->findFileByNip(
                        $identitas,
                        $folderId,
                        $jenis
                    );
                } catch (\Exception $e) {
                    $this->error("ERROR API: " . $e->getMessage());
                    continue;
                }

                $oldFile = $row->kumpulan_file;

                if (($result['status'] ?? 0) == 1) {

                    $row->update([
                        'kumpulan_file' => $result['file_url'],
                        'kumpulan_sync' => 1
                    ]);

                    // hapus file lama VPS
                    if (!empty($oldFile)) {

                        $relativePath = parse_url($oldFile, PHP_URL_PATH);
                        $relativePath = ltrim($relativePath, '/');
                        $localPath    = public_path($relativePath);

                        if (file_exists($localPath)) {
                            if (unlink($localPath)) {
                                $this->info("{$identitas} → File VPS dihapus");
                            } else {
                                $this->warn("{$identitas} → Gagal hapus file VPS");
                            }
                        } else {
                            $this->warn("{$identitas} → File tidak ditemukan di VPS");
                        }
                    }

                    $this->info("{$identitas} → SYNC BERHASIL");
                } else {

                    $this->warn("{$identitas} → FILE TIDAK ADA DI DRIVE");
                }
                }
            });

        $this->info("Sinkronisasi {$jenis} selesai!");
        return Command::SUCCESS;
    }
}