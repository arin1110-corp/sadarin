<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ModelEselon;


class EselonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ModelEselon::truncate();
        ModelEselon::create(
            [
                'eselon_nama' => 'Eselon IA',
                'eselon_status' => 1,
            ]
        );
        ModelEselon::create(
            [
                'eselon_nama' => 'Eselon IB',
                'eselon_status' => 1,
            ]
        );
        ModelEselon::create(
            [
                'eselon_nama' => 'Eselon IIA',
                'eselon_status' => 1,
            ]
        );
        ModelEselon::create(
            [
                'eselon_nama' => 'Eselon IIB',
                'eselon_status' => 1,
            ]
        );
        ModelEselon::create(
            [
                'eselon_nama' => 'Eselon IIIA',
                'eselon_status' => 1,
            ]
        );
        ModelEselon::create(
            [
                'eselon_nama' => 'Eselon IIIB',
                'eselon_status' => 1,
            ]
        );
        ModelEselon::create(
            [
                'eselon_nama' => 'Eselon IVA',
                'eselon_status' => 1,
            ]
        );
        ModelEselon::create(
            [
                'eselon_nama' => 'Eselon IVB',
                'eselon_status' => 1,
            ]
        );
    }
}
