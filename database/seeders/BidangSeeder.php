<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ModelBidang;
use Illuminate\Database\Seeder;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        //

        // Kosongkan tabel users dan reset auto-increment ID
        ModelBidang::truncate();
        ModelBidang::create(
            [
                'bidang_nama' => 'Sekretariat',
                'bidang_instansi' => 'Dinas Kebudayaan Provinsi Bali',
                'bidang_link' => 'data.sekretariat',
                'bidang_status' => 1,
            ]
        );
        ModelBidang::create(
            [
                'bidang_nama' => 'Bidang Kesenian',
                'bidang_instansi' => 'Dinas Kebudayaan Provinsi Bali',
                'bidang_link' => 'data.kesenian',
                'bidang_status' => 1,
            ]
        );
        ModelBidang::create(
            [
                'bidang_nama' => 'Bidang Cagar Budaya dan Permuseuman',
                'bidang_link' => 'data.cagar-budaya',
                'bidang_instansi' => 'Dinas Kebudayaan Provinsi Bali',
                'bidang_status' => 1,
            ]
        );
        ModelBidang::create(
            [
                'bidang_nama' => 'Bidang Tradisi dan Warisan Budaya',
                'bidang_link' => 'data.tradisi',
                'bidang_instansi' => 'Dinas Kebudayaan Provinsi Bali',
                'bidang_status' => 1,
            ]
        );
        ModelBidang::create(
            [
                'bidang_nama' => 'Bidang Sejarah dan Dokumentasi Kebudayaan',
                'bidang_link' => 'data.sejarah',
                'bidang_instansi' => 'Dinas Kebudayaan Provinsi Bali',
                'bidang_status' => 1,
            ]
        );
        ModelBidang::create(
            [
                'bidang_nama' => 'UPTD. Museum Bali',
                'bidang_link' => 'data.museum',
                'bidang_instansi' => 'Dinas Kebudayaan Provinsi Bali',
                'bidang_status' => 1,
            ]
        );
        ModelBidang::create(
            [
                'bidang_nama' => 'UPTD. Taman Budaya',
                'bidang_link' => 'data.taman-budaya',
                'bidang_instansi' => 'Dinas Kebudayaan Provinsi Bali',
                'bidang_status' => 1,
            ]
        );
        ModelBidang::create(
            [
                'bidang_nama' => 'UPTD. Monumen Perjuangan Rakyat Bali',
                'bidang_link' => 'data.monumen',
                'bidang_instansi' => 'Dinas Kebudayaan Provinsi Bali',
                'bidang_status' => 1,
            ]
        );
    }
}
