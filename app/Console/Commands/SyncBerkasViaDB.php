<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ModelPengumpulanBerkas;
use App\Services\GoogleDriveServiceDB;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SyncBerkasViaDB extends Command
{
    protected $signature = 'sync:berkas-db {jenis}';
    protected $description = 'Sinkronisasi berkas + hapus file VPS jika sudah ada di Google Drive';

    public function handle(GoogleDriveServiceDB $googleDrive)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $jenis = strtolower($this->argument('jenis'));
        $this->info("Mulai sinkronisasi {$jenis}...");

        // 🔥 Ambil tombol
        $tombol = DB::table('sadarin_tombolberkas')->where('tombol_prefix', $jenis)->first();

        if (!$tombol) {
            $this->error("Jenis '{$jenis}' tidak ditemukan!");
            return Command::FAILURE;
        }


        // 🔥 Ambil JSON config
        $json = DB::table('sadarin_json')->where('json_id', $tombol->tombol_json)->first();

        if (!$json) {
            $this->error('JSON config tidak ditemukan!');
            return Command::FAILURE;
        }

        // 🔥 Ambil mapping SEKALI (biar gak query di loop)
        $mappingList = DB::table('sadarin_mappingtombol')->where('mapping_tombol', $tombol->tombol_id)->get()->keyBy('mapping_jeniskerja');

        ModelPengumpulanBerkas::query()
            ->select('sadarin_pengumpulanberkas.*', 'sadarin_user.user_jeniskerja')
            ->leftJoin('sadarin_user', function ($join) {
            $join->on('sadarin_pengumpulanberkas.kumpulan_user', '=', 'sadarin_user.user_nip')->orOn('sadarin_pengumpulanberkas.kumpulan_user', '=', 'sadarin_user.user_nik');
            })
            ->where('sadarin_pengumpulanberkas.kumpulan_jenis', $tombol->tombol_nama)
            ->where('sadarin_pengumpulanberkas.kumpulan_status', 1)
            ->where('sadarin_pengumpulanberkas.kumpulan_sync', 0)
            ->chunk(100, function ($rows) use ($googleDrive, $json, $mappingList, $tombol) {
                foreach ($rows as $row) {
                    $identitas = $row->kumpulan_user;

                    if (!$identitas || !$row->user_jeniskerja) {
                        continue;
                    }

                // 🔥 Ambil mapping dari cache (tanpa query ulang)
                $mapping = $mappingList[$row->user_jeniskerja] ?? null;

                if (!$mapping) {
                    $this->warn("Mapping tidak ditemukan untuk {$identitas}");
                        continue;
                    }

                $folderId = $mapping->mapping_folderid;

                usleep(300000);

                // 🔥 Cari file di Google Drive
                $result = $googleDrive->findFileByNip($identitas, $folderId, $json->json_file);

                    $oldFile = $row->kumpulan_file;

                    if (($result['status'] ?? 0) == 1) {
                    DB::transaction(function () use ($row, $result, $oldFile, $identitas) {
                        // ✅ Update ke link Google Drive
                        $row->update([
                            'kumpulan_file' => $result['file_url'],
                            'kumpulan_status' => 1,
                            'kumpulan_sync' => 1,
                            ]);

                        // 🔥 Hapus file VPS lama
                        if (!empty($oldFile)) {
                                $relativePath = parse_url($oldFile, PHP_URL_PATH);
                            $relativePath = ltrim($relativePath, '/');
                                $localPath = public_path($relativePath);

                                if (file_exists($localPath)) {
                                    if (unlink($localPath)) {
                                    echo "{$identitas} → File VPS dihapus\n";
                                    } else {
                                    echo "{$identitas} → Gagal hapus VPS\n";
                                    }
                                } else {
                                echo "{$identitas} → File VPS tidak ditemukan\n";
                                }
                            }
                    });
                    } else {
                    // ❌ Tidak ditemukan di Drive
                    $row->update([
                        'kumpulan_status' => 1,
                        ]);
                    }

                    $this->info("{$identitas} → Sinkron selesai");
                }
            });

        $this->info("Sinkronisasi {$jenis} selesai!");
        return Command::SUCCESS;
    }
}