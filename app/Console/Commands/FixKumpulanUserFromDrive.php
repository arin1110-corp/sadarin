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
        $jenis = strtolower($this->argument('jenis'));

        $tombol = DB::table('sadarin_tombolberkas')
            ->where('tombol_prefix', $jenis)
            ->first();

        $mappingList = DB::table('sadarin_mappingtombol')
            ->where('mapping_tombol', $tombol->tombol_id)
            ->get();

        foreach ($mappingList as $mapping) {

            $folderId = $mapping->mapping_folderid;

            $files = $googleDrive->listFiles($folderId);

            foreach ($files as $file) {

                $name = $file->name;

                // ambil NIP dari nama file
                $nip = explode('_', $name)[0];

                // update ke DB berdasarkan nama file
                DB::table('sadarin_pengumpulanberkas')
                    ->where('kumpulan_file', 'like', '%' . $name)
                    ->update([
                        'kumpulan_user' => $nip
                    ]);

                $this->info("Fix: $name → $nip");
            }
        }

        $this->info("Selesai recovery!");
    }
}