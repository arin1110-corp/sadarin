<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ModelJabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ModelJabatan::truncate();
        $jabatans = [
            'Analis Apresiasi Karya Seni',
            'Analis Budaya',
            'Analis Cagar Budaya dan Koleksi Museum',
            'Analis Informasi Kebudayaan',
            'Analis Keuangan Pusat dan Daerah Ahli Muda',
            'Analis Koleksi Museum',
            'Analis Nilai Budaya',
            'Analis Organisasi',
            'Analis Pelaksanaan Program Sertifikasi SDM Kebudayaan',
            'Analis Pelestarian Cagar Budaya dan Permuseuman',
            'Analis Perencanaan, Evaluasi dan Pelaporan',
            'Analis Sumber Daya Manusia Aparatur',
            'Analis Warisan Budaya',
            'Analisis Pelayanan',
            'Analisis Pelayanan Publik',
            'Analisis Sumber Sejarah',
            'Bendahara',
            'Filolog',
            'Kepala Bidang',
            'Kepala Dinas',
            'Kepala Seksi Dokumentasi dan Informasi',
            'Kepala Seksi Edukasi dan Preparasi',
            'Kepala Seksi Informasi Masyarakat',
            'Kepala Seksi Koleksi dan Konservasi',
            'Kepala Seksi Penelitian dan Pengembangan',
            'Kepala Seksi Penyajian dan Pengembangan Seni',
            'Kepala Sub Bagian Tata Usaha',
            'Kepala Sub Bagian Umum',
            'Kepala UPTD',
            'Konservator',
            'Kurator Koleksi Museum',
            'Pamong Budaya Ahli Muda',
            'Pamong Budaya Ahli Pertama',
            'Pemandu Museum',
            'Pemelihara Koleksi dan Museum',
            'Penata Laporan Keuangan',
            'Penelaah Kebijakan Pengadaan Barang dan Jasa',
            'Penerjemah Ahli Muda',
            'Pengadministrasi Umum',
            'Pengelola Data Kesenian dan Perfilman',
            'Pengelola Data Layanan Informasi dan Edukasi Publik',
            'Pengelola Data Warisan Budaya',
            'Pengelola Gaji',
            'Pengelola Kepegawaian',
            'Pengelola Keuangan',
            'Pengelola Musuem dan Koleksi Benda Seni',
            'Pengelola Program dan Kegiatan',
            'Pengelola Retribusi Daerah',
            'Pengelola Sarana dan Prasarana Kantor',
            'Pengelola Surat',
            'Penglola Data',
            'Pranata Komputer Ahli Pertama',
            'Pustakawan Ahli Madya',
            'Sekretaris',
            'Teknisi Panggung',
            'Verifikator Keuangan',
            'Penata Layanan Operasional',
            'Pengadministrasi Perkantoran',
            'Operator Layanan Operasional',
            'Pengelola Umum Operasional',
            'Arsiparis Ahli Muda',
            'Arsiparis Ahli Pertama',
            'Arsiparis Terampil',
            'Arsiparis Mahir',
            'PPPK',
        ];
        foreach (array_unique($jabatans) as $jabatan) {
            ModelJabatan::create([
                'jabatan_nama' => $jabatan,
                'jabatan_status' => 1
            ]);
        }
    }
}
