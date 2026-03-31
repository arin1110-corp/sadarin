<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ModelPengumpulanBerkas;
use App\Services\GoogleDriveServiceDB; // versi DB
use Illuminate\Support\Facades\DB;

class SyncBerkasViaDB extends Command
{
    protected $signature = 'sync:berkas {jenis}';
    protected $description = 'Sinkronisasi berkas + hapus file VPS jika sudah ada di Google Drive';

    public function handle(GoogleDriveServiceDB $googleDrive)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $jenis = strtolower($this->argument('jenis'));
        $this->info("Mulai sinkronisasi {$jenis}...");

        // Ambil tombol berkas (MASTER)
        $tombol = DB::table('sadarin_tombolberkas')
            ->where('tombol_prefix', $jenis)
            ->first();

        if (!$tombol) {
            $this->error("Jenis '{$jenis}' tidak ditemukan di DB!");
            return Command::FAILURE;
        }

        // Ambil JSON config
        $json = DB::table('sadarin_json')
            ->where('json_id', $tombol->tombol_json)
            ->first();

        if (!$json) {
            $this->error("JSON config tidak ditemukan!");
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
            ->where('sadarin_pengumpulanberkas.kumpulan_jenis', $tombol->tombol_nama)
            ->where('sadarin_pengumpulanberkas.kumpulan_status', 1)
            ->where('sadarin_pengumpulanberkas.kumpulan_sync', 0)
            ->chunk(100, function ($rows) use ($googleDrive, $jenis, $json) {

                foreach ($rows as $row) {

                    $identitas = $row->kumpulan_user;

                    if (!$identitas || !$row->user_jeniskerja) {
                        continue;
                    }

                // Ambil folder mapping dari DB
                $folder = DB::table('sadarin_prefixberkas')
                        ->where('prefix', $jenis)
                        ->where('jenis_kerja', $row->user_jeniskerja)
                        ->first();

                    if (!$folder) {
                    $this->warn("Folder mapping untuk {$identitas} tidak ditemukan.");
                        continue;
                    }

                $folderId = $folder->folder_id;          // ID folder di Google Drive
                $folderServer = $folder->mapping_folder; // path server VPS

                usleep(300000); // jeda supaya Drive tidak limit

                // Cari file di Google Drive
                $result = $googleDrive->findFileByNip(
                        $identitas,
                        $folderId,
                        $json->json_file
                    );

                    $oldFile = $row->kumpulan_file;

                    if (($result['status'] ?? 0) == 1) {

                    // File ditemukan di Drive → update DB & hapus file VPS
                    DB::transaction(function () use ($row, $result, $oldFile, $identitas, $folderServer) {

                        // Update DB ke link Drive
                        $row->update([
                                'kumpulan_file'   => $result['file_url'],
                                'kumpulan_status' => 1,
                                'kumpulan_sync'   => 1
                            ]);

                        // Hapus file VPS lama
                        if (!empty($oldFile)) {
                                $relativePath = parse_url($oldFile, PHP_URL_PATH);
                            $relativePath = ltrim($relativePath, '/');
                                $localPath = public_path($relativePath);

                                if (file_exists($localPath)) {
                                    if (unlink($localPath)) {
                                    echo "{$identitas} → File VPS berhasil dihapus\n";
                                    } else {
                                    echo "{$identitas} → Gagal hapus file VPS\n";
                                    }
                                } else {
                                echo "{$identitas} → File VPS tidak ditemukan\n";
                                }
                            }
                        });

                    } else {
                    // File belum ada di Drive, tetap status 1 tapi sync 0
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