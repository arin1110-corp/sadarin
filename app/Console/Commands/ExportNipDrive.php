<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\GoogleDriveServiceDB;

class ExportNipDrive extends Command
{
    protected $signature = 'export:nip_drive {jenis}';
    protected $description = 'Export NIP dan link file dari Google Drive ke CSV';

    public function handle(GoogleDriveServiceDB $googleDrive)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $jenis = strtolower($this->argument('jenis'));
        $this->info("Mulai export {$jenis}...");

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

        // 🔥 Siapkan file CSV
        $filename = storage_path("app/nip_drive_{$jenis}.csv");
        $fp = fopen($filename, 'w');

        // Header CSV
        fputcsv($fp, ['NIP', 'File Name', 'Link']);

        $total = 0;

        foreach ($mappingList as $mapping) {

            $folderId = $mapping->mapping_folderid;
            $this->info("Scan folder: {$folderId}");

            // 🔥 Ambil file dari Drive
            $files = $googleDrive->listFiles($folderId, $json->json_file);

            foreach ($files as $file) {

                $name = $file->name;

                // 🔥 Extract NIP dari nama file
                $nip = explode('_', $name)[0];

                // 🔒 Validasi NIP (angka saja)
                if (!preg_match('/^\d{8,20}$/', $nip)) {
                    continue;
                }

                // 🔥 Tulis ke CSV
                fputcsv($fp, [
                    $nip,
                    $name,
                    $file->webViewLink
                ]);

                $total++;

                // ⏳ Delay kecil biar aman API
                usleep(100000); // 0.1 detik
            }
        }

        fclose($fp);

        $this->info("Selesai! Total data: {$total}");
        $this->info("File tersimpan di: {$filename}");

        return Command::SUCCESS;
    }
}