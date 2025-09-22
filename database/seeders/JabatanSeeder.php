<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModelJabatan;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua data dan reset auto increment
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ModelJabatan::truncate();
        DB::statement('ALTER TABLE sadarin_jabatan AUTO_INCREMENT = 1;');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $jabatans = [
            ['nama' => 'Analis Apresiasi Karya Seni', 'kategori' => 'Struktural'],
            ['nama' => 'Analis Budaya', 'kategori' => 'Struktural'],
            ['nama' => 'Analis Cagar Budaya dan Koleksi Museum', 'kategori' => 'Struktural'],
            ['nama' => 'Analis Informasi Kebudayaan', 'kategori' => 'Struktural'],
            ['nama' => 'Analis Keuangan Pusat dan Daerah Ahli Muda', 'kategori' => 'Struktural'],
            ['nama' => 'Analis Koleksi Museum', 'kategori' => 'Struktural'],
            ['nama' => 'Analis Nilai Budaya', 'kategori' => 'Struktural'],
            ['nama' => 'Analis Organisasi', 'kategori' => 'Struktural'],
            ['nama' => 'Analis Pelaksanaan Program Sertifikasi SDM Kebudayaan', 'kategori' => 'Struktural'],
            ['nama' => 'Analis Pelestarian Cagar Budaya dan Permuseuman', 'kategori' => 'Struktural'],
            ['nama' => 'Analis Perencanaan, Evaluasi dan Pelaporan', 'kategori' => 'Struktural'],
            ['nama' => 'Analis Sumber Daya Manusia Aparatur', 'kategori' => 'Struktural'],
            ['nama' => 'Analis Warisan Budaya', 'kategori' => 'Struktural'],
            ['nama' => 'Analisis Pelayanan', 'kategori' => 'Struktural'],
            ['nama' => 'Analisis Pelayanan Publik', 'kategori' => 'Struktural'],
            ['nama' => 'Analisis Sumber Sejarah', 'kategori' => 'Struktural'],
            ['nama' => 'Bendahara', 'kategori' => 'Struktural'],
            ['nama' => 'Filolog', 'kategori' => 'Fungsional'],
            ['nama' => 'Kepala Bidang', 'kategori' => 'Struktural'],
            ['nama' => 'Kepala Dinas', 'kategori' => 'Struktural'],
            ['nama' => 'Kepala Seksi Dokumentasi dan Informasi', 'kategori' => 'Struktural'],
            ['nama' => 'Kepala Seksi Edukasi dan Preparasi', 'kategori' => 'Struktural'],
            ['nama' => 'Kepala Seksi Informasi Masyarakat', 'kategori' => 'Struktural'],
            ['nama' => 'Kepala Seksi Koleksi dan Konservasi', 'kategori' => 'Struktural'],
            ['nama' => 'Kepala Seksi Penelitian dan Pengembangan', 'kategori' => 'Struktural'],
            ['nama' => 'Kepala Seksi Penyajian dan Pengembangan Seni', 'kategori' => 'Struktural'],
            ['nama' => 'Kepala Sub Bagian Tata Usaha', 'kategori' => 'Struktural'],
            ['nama' => 'Kepala Sub Bagian Umum', 'kategori' => 'Struktural'],
            ['nama' => 'Kepala UPTD', 'kategori' => 'Struktural'],
            ['nama' => 'Konservator', 'kategori' => 'Fungsional'],
            ['nama' => 'Kurator Koleksi Museum', 'kategori' => 'Fungsional'],
            ['nama' => 'Pamong Budaya Ahli Muda', 'kategori' => 'Fungsional'],
            ['nama' => 'Pamong Budaya Ahli Pertama', 'kategori' => 'Fungsional'],
            ['nama' => 'Pemandu Museum', 'kategori' => 'Fungsional'],
            ['nama' => 'Pemelihara Koleksi dan Museum', 'kategori' => 'Fungsional'],
            ['nama' => 'Penata Laporan Keuangan', 'kategori' => 'Struktural'],
            ['nama' => 'Penelaah Kebijakan Pengadaan Barang dan Jasa', 'kategori' => 'Fungsional'],
            ['nama' => 'Penerjemah Ahli Muda', 'kategori' => 'Fungsional'],
            ['nama' => 'Pengadministrasi Umum', 'kategori' => 'Fungsional'],
            ['nama' => 'Pengelola Data Kesenian dan Perfilman', 'kategori' => 'Fungsional'],
            ['nama' => 'Pengelola Data Layanan Informasi dan Edukasi Publik', 'kategori' => 'Fungsional'],
            ['nama' => 'Pengelola Data Warisan Budaya', 'kategori' => 'Fungsional'],
            ['nama' => 'Pengelola Gaji', 'kategori' => 'Struktural'],
            ['nama' => 'Pengelola Kepegawaian', 'kategori' => 'Struktural'],
            ['nama' => 'Pengelola Keuangan', 'kategori' => 'Struktural'],
            ['nama' => 'Pengelola Musuem dan Koleksi Benda Seni', 'kategori' => 'Fungsional'],
            ['nama' => 'Pengelola Program dan Kegiatan', 'kategori' => 'Struktural'],
            ['nama' => 'Pengelola Retribusi Daerah', 'kategori' => 'Struktural'],
            ['nama' => 'Pengelola Sarana dan Prasarana Kantor', 'kategori' => 'Fungsional'],
            ['nama' => 'Pengelola Surat', 'kategori' => 'Struktural'],
            ['nama' => 'Penglola Data', 'kategori' => 'Fungsional'],
            ['nama' => 'Pranata Komputer Ahli Pertama', 'kategori' => 'Fungsional'],
            ['nama' => 'Pustakawan Ahli Madya', 'kategori' => 'Fungsional'],
            ['nama' => 'Sekretaris', 'kategori' => 'Struktural'],
            ['nama' => 'Teknisi Panggung', 'kategori' => 'Fungsional'],
            ['nama' => 'Verifikator Keuangan', 'kategori' => 'Fungsional'],
            ['nama' => 'Penata Layanan Operasional', 'kategori' => 'Fungsional'],
            ['nama' => 'Pengadministrasi Perkantoran', 'kategori' => 'Fungsional'],
            ['nama' => 'Operator Layanan Operasional', 'kategori' => 'Fungsional'],
            ['nama' => 'Pengelola Umum Operasional', 'kategori' => 'Fungsional'],
            ['nama' => 'Arsiparis Ahli Muda', 'kategori' => 'Fungsional'],
            ['nama' => 'Arsiparis Ahli Pertama', 'kategori' => 'Fungsional'],
            ['nama' => 'Arsiparis Terampil', 'kategori' => 'Fungsional'],
            ['nama' => 'Arsiparis Mahir', 'kategori' => 'Fungsional'],
            ['nama' => 'PPPK', 'kategori' => 'Fungsional'],
        ];

        foreach ($jabatans as $jabatan) {
            ModelJabatan::create([
                'jabatan_nama' => $jabatan['nama'],
                'jabatan_kategori' => $jabatan['kategori'],
                'jabatan_status' => 1,
            ]);
        }
    }
}
