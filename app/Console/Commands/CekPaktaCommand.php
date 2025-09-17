<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\ModelPakta;
use App\Models\ModelUser;
use Google\Service\Bigquery\Model;

class CekPaktaCommand extends Command
{
    protected $signature = 'cek:pakta';
    protected $description = 'Cek file pakta integritas untuk semua pegawai';

    public function handle()
    {
        $jenisMap = [
            'pns' => 1,
            'pppk' => 2,
        ];

        $nipDariFiles = [];

        foreach ($jenisMap as $folder => $jenis) {
            $files = Storage::files("pakta_integritas/{$folder}");

            foreach ($files as $file) {
                $filename = pathinfo($file, PATHINFO_FILENAME);

                // Ambil 18 digit pertama dari nama file
                if (preg_match('/^(\d{18})/u', $filename, $match)) {
                    $nip = $match[1];
                    $nipDariFiles[$jenis][$nip] = $file;
                }
            }
        }

        $pegawaiList = ModelUser::all();

        foreach ($pegawaiList as $pegawai) {
            $jenis = $pegawai->user_jeniskerjaan; // 1 untuk PNS, 2 untuk PPPK

            if (isset($nipDariFiles[$jenis][$pegawai->user_nip])) {
                ModelPakta::updateOrCreate(
                    ['nip' => $pegawai->user_nip, 'jenis_pegawai' => $jenis],
                    [
                        'file_link' => $nipDariFiles[$jenis][$pegawai->nip],
                        'status' => 'Ngumpul',
                    ]
                );
                $this->info("✔ {$pegawai->nip} ({$pegawai->nama}) - Ngumpul");
            } else {
                ModelPakta::updateOrCreate(
                    ['nip' => $pegawai->nip, 'jenis_pegawai' => $jenis],
                    [
                        'file_link' => null,
                        'status' => 'Tidak Ngumpul',
                    ]
                );
                $this->warn("✘ {$pegawai->nip} ({$pegawai->nama}) - Tidak Ngumpul");
            }
        }

        $this->info("Selesai cek pakta integritas.");
    }
}
