<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\GoogleDriveServiceDB;

class FixKumpulanUserFromDrive extends Command
{
    protected $signature = 'fix:kumpulan_user_drive {jenis}';
    protected $description = 'Recovery kumpulan_user dari nama file Google Drive';

    public function handle(GoogleDriveServiceDB $googleDrive)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $jenis = strtolower($this->argument('jenis'));
        $this->info("Mulai recovery {$jenis}...");

        // 🔥 Ambil tombol
        $tombol = DB::table('sadarin_tombolberkas')
            ->where('tombol_prefix', $jenis)
            ->first();

        if (!$tombol) {
            $this->error("Jenis '{$jenis}' tidak ditemukan!");
            return Command::FAILURE;
        }

        // 🔥 Ambil JSON config
        $json = DB::table('sadarin_json')
            ->where('json_id', $tombol->tombol_json)
            ->first();

        if (!$json) {
            $this->error('JSON config tidak ditemukan!');
            return Command::FAILURE;
        }

        // 🔥 Ambil mapping folder
        $mappingList = DB::table('sadarin_mappingtombol')
            ->where('mapping_tombol', $tombol->tombol_id)
            ->get();

        foreach ($mappingList as $mapping) {

            $folderId = $mapping->mapping_folderid;
            $this->info("Scan folder: {$folderId}");

            // 🔥 Ambil file dari Drive
            $files = $googleDrive->listFiles($folderId, $json->json_file);

            foreach ($files as $file) {

                $name = $file->name;

                // 🔥 Extract NIP dari nama file
                $nip = explode('_', $name)[0];

                // 🔒 Validasi NIP (angka saja, min 8 digit)
                if (!preg_match('/^\d{8,20}$/', $nip)) {
                    $this->warn("Skip (format tidak valid): $name");
                    continue;
                }

                // 🔥 Update hanya data yang rusak
                $updated = DB::table('sadarin_pengumpulanberkas')
                    ->where('kumpulan_file', 'like', '%' . $name)
                    ->where('kumpulan_user', '198608162025212001') // ⬅️ hanya yang rusak
                    ->update([
                        'kumpulan_user' => $nip
                    ]);

                if ($updated) {
                    $this->info("✔ Fix: $name → $nip");
                }

                // ⏳ Delay biar aman dari limit API
                usleep(200000);
            }
        }

        $this->info("Recovery selesai!");
        return Command::SUCCESS;
    }
}