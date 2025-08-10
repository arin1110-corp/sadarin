<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ModelSubBag;

class SubbagSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ModelSubBag::truncate();
        ModelSubBag::create(
            [
                'subbag_nama' => 'Subbagian Umum dan Kepegawaian',
                'subbag_bidang' => 1,
                'subbag_link' => 'data.umpeg',
                'subbag_status' => 1,
            ]
        );
        ModelSubBag::create(
            [
                'subbag_nama' => 'Subbagian Keuangan',
                'subbag_bidang' => 1,
                'subbag_link' => 'data.keuangan',
                'subbag_status' => 1,
            ]
        );
        ModelSubBag::create(
            [
                'subbag_nama' => 'Subbagian Penyusunan Program Evaluasi dan Pelaporan (PPEP)',
                'subbag_bidang' => 1,
                'subbag_link' => 'data.ppep',
                'subbag_status' => 1,
            ]
        );
    }
}
