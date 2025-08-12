<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ModelSubNavigasiSekretariat;

class SubNavigasiSekre extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ModelSubNavigasiSekretariat::truncate();
        ModelSubNavigasiSekretariat::create(
            [
                'subnavigasisekre_nama' => '2025',
                'subnavigasisekre_urutan' => 1,
                'subnavigasisekre_navigasisekre' => 1,
                'subnavigasisekre_link' => 'https://drive.google.com/drive/folders/1tfPKg6MIK0yueHfcqqAJ81m2l7O-aB_G',
                'subnavigasisekre_status' => 1,
            ],
        );
        ModelSubNavigasiSekretariat::create(
            [
                'subnavigasisekre_nama' => 'PNS',
                'subnavigasisekre_urutan' => 2,
                'subnavigasisekre_navigasisekre' => 3,
                'subnavigasisekre_link' => 'data-pegawaipns',
                'subnavigasisekre_status' => 1,
            ],
        );
    }
}
