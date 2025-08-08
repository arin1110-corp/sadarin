<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ModelJenisKerja;

class JenisKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ModelJenisKerja::truncate();
        $jenisKerja = [
            ['jeniskerja_nama' => 'Pegawai Negeri Sipil', 'jeniskerja_singkatan' => 'PNS', 'jeniskerja_status' => 1],
            ['jeniskerja_nama' => 'Pegawai Pemerintah dengan Perjanjian Kerja', 'jeniskerja_singkatan' => 'PPPK', 'jeniskerja_status' => 1],
            ['jeniskerja_nama' => 'Pegawai Kontrak', 'jeniskerja_singkatan' => 'Non ASN', 'jeniskerja_status' => 1],
            ['jeniskerja_nama' => 'Tenaga Honorer', 'jeniskerja_singkatan' => 'TH', 'jeniskerja_status' => 1],
            ['jeniskerja_nama' => 'Tenaga Ahli', 'jeniskerja_singkatan' => 'TA', 'jeniskerja_status' => 1],
            ['jeniskerja_nama' => 'Tenaga Pendukung', 'jeniskerja_singkatan' => 'TP', 'jeniskerja_status' => 1],
            ['jeniskerja_nama' => 'Tenaga Administrasi', 'jeniskerja_singkatan' => 'TA', 'jeniskerja_status' => 1],
            ['jeniskerja_nama' => 'Tenaga Teknis', 'jeniskerja_singkatan' => 'TT', 'jeniskerja_status' => 1],
        ];
        foreach ($jenisKerja as $jenisKerjas) {
            ModelJenisKerja::create([
                'jeniskerja_nama' => $jenisKerjas['jeniskerja_nama'],
                'jeniskerja_status' => $jenisKerjas['jeniskerja_status'],
                'jeniskerja_singkatan' => $jenisKerjas['jeniskerja_singkatan'],
            ]);
        }
    }
}
