<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ModelNavigasiSekretariat;
use Google\Service\Bigquery\Model;
use PhpParser\Node\Expr\AssignOp\Mod;

class NavigasiSekreSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ModelNavigasiSekretariat::truncate();
        ModelNavigasiSekretariat::create(
            [
                'navigasisekre_nama' => 'Dokumen Pelaksanaan Anggaran',
                'navigasisekre_deskripsi' => 'Berisi dokumen Dokumen Pelakasanaan Anggaran (DPA) pada Dinas Kebudayaan Provinsi Bali.',
                'navigasisekre_urutan' => 1,
                'navigasisekre_subbag' => 3,
                'navigasisekre_level' => 1,
                'navigasisekre_status' => 1,
            ],
            ModelNavigasiSekretariat::create(
                [
                    'navigasisekre_nama' => 'Rencana Anggaran Kas',
                    'navigasisekre_deskripsi' => 'Berisi dokumen Rencana Anggaran Kas (RAK) pada Dinas Kebudayaan Provinsi Bali.',
                    'navigasisekre_urutan' => 2,
                    'navigasisekre_subbag' => 3,
                    'navigasisekre_level' => 1,
                    'navigasisekre_status' => 1,
                ]
            ),
            ModelNavigasiSekretariat::create(
                [
                    'navigasisekre_nama' => 'Laporan Keuangan',
                    'navigasisekre_deskripsi' => 'Berisi dokumen Laporan Keuangan pada Dinas Kebudayaan Provinsi Bali.',
                    'navigasisekre_urutan' => 3,
                    'navigasisekre_subbag' => 2,
                    'navigasisekre_level' => 1,
                    'navigasisekre_status' => 1,
                ]
            ),
            ModelNavigasiSekretariat::create(
                [
                    'navigasisekre_nama' => 'Pegawai Dinas Kebudayaan Provinsi Bali',
                    'navigasisekre_deskripsi' => 'Berisi daftar pegawai Dinas Kebudayaan Provinsi Bali.',
                    'navigasisekre_urutan' => 4,
                    'navigasisekre_subbag' => 1,
                    'navigasisekre_level' => 1,
                    'navigasisekre_status' => 1,
                ]
            )
        );
    }
}
