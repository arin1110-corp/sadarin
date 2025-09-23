<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PendidikanSeeder extends Seeder
{
    public function run()
    {
        $pendidikans = [];

        // ===== SD / MI =====
        $jenjangSD = ['SD', 'MI'];
        foreach ($jenjangSD as $jenjang) {
            $pendidikans[] = ['pendidikan_jenjang' => $jenjang, 'pendidikan_jurusan' => '-', 'pendidikan_status' => 1];
        }

        // ===== SMP / MTs =====
        $jenjangSMP = ['SMP', 'MTS'];
        foreach ($jenjangSMP as $jenjang) {
            $pendidikans[] = ['pendidikan_jenjang' => $jenjang, 'pendidikan_jurusan' => '-', 'pendidikan_status' => 1];
        }

        // ===== SMA / MA / SMK =====
        $jurusanSMA = ['IPA', 'IPS', 'Bahasa', 'Keagamaan', 'Pariwisata', 'Teknik', 'Akuntansi', 'Kesehatan', 'Administrasi'];
        $jenjangSMA = ['SMA', 'MA', 'SMK'];
        foreach ($jenjangSMA as $jenjang) {
            foreach ($jurusanSMA as $jurusan) {
                $pendidikans[] = ['pendidikan_jenjang' => $jenjang, 'pendidikan_jurusan' => $jurusan, 'pendidikan_status' => 1];
            }
        }

        // ===== D1 / D2 / D3 =====
        $jenjangD = ['D1', 'D2', 'D3'];
        $jurusanD = ['Teknik Informatika', 'Manajemen Informatika', 'Akuntansi', 'Administrasi', 'Manajemen', 'Kesehatan', 'Pariwisata', 'Pendidikan Guru SD', 'Pendidikan Guru PAUD', 'Pendidikan Guru SMP', 'Pendidikan Guru SMA'];
        foreach ($jenjangD as $jenjang) {
            foreach ($jurusanD as $jurusan) {
                $pendidikans[] = ['pendidikan_jenjang' => $jenjang, 'pendidikan_jurusan' => $jurusan, 'pendidikan_status' => 1];
            }
        }

        // ===== S1 =====
        $jenjangS1 = ['S1'];
        $jurusanS1 = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Akuntansi',
            'Manajemen',
            'Hukum',
            'Psikologi',
            'Pendidikan Guru SD',
            'Pendidikan Guru PAUD',
            'Pendidikan Matematika',
            'Pendidikan Bahasa Indonesia',
            'Pendidikan Bahasa Inggris',
            'Pendidikan IPA',
            'Pendidikan IPS',
            'Pendidikan Seni',
            'Pendidikan PJOK',
            'Pendidikan Agama',
            'Kedokteran',
            'Farmasi',
            'Keperawatan',
            'Kesehatan Masyarakat',
            'Pariwisata',
            'Seni Rupa',
            'Seni Musik',
            'Seni Tari',
            'Teknik Mesin',
            'Teknik Elektro',
            'Teknik Sipil',
            'Teknik Kimia',
            'Teknik Industri',
            'Teknik Lingkungan'
        ];
        foreach ($jenjangS1 as $jenjang) {
            foreach ($jurusanS1 as $jurusan) {
                $pendidikans[] = ['pendidikan_jenjang' => $jenjang, 'pendidikan_jurusan' => $jurusan, 'pendidikan_status' => 1];
            }
        }

        // ===== S2 =====
        $jenjangS2 = ['S2'];
        $jurusanS2 = [
            'Teknik Informatika',
            'Manajemen',
            'Hukum',
            'Akuntansi',
            'Pendidikan Matematika',
            'Pendidikan Bahasa',
            'Pendidikan IPA',
            'Pendidikan IPS',
            'Pendidikan Seni',
            'Pendidikan PJOK',
            'Pendidikan Agama',
            'Kedokteran',
            'Farmasi',
            'Keperawatan',
            'Kesehatan Masyarakat',
            'Pariwisata'
        ];
        foreach ($jenjangS2 as $jenjang) {
            foreach ($jurusanS2 as $jurusan) {
                $pendidikans[] = ['pendidikan_jenjang' => $jenjang, 'pendidikan_jurusan' => $jurusan, 'pendidikan_status' => 1];
            }
        }

        // ===== S3 =====
        $jenjangS3 = ['S3'];
        $jurusanS3 = [
            'Teknik Informatika',
            'Manajemen',
            'Hukum',
            'Akuntansi',
            'Pendidikan',
            'Kedokteran',
            'Kesehatan',
            'Pariwisata',
            'Seni'
        ];
        foreach ($jenjangS3 as $jenjang) {
            foreach ($jurusanS3 as $jurusan) {
                $pendidikans[] = ['pendidikan_jenjang' => $jenjang, 'pendidikan_jurusan' => $jurusan, 'pendidikan_status' => 1];
            }
        }

        // Insert ke database
        DB::table('sadarin_pendidikan')->insert($pendidikans);

        $this->command->info('Seeder Pendidikan lengkap ±300 entri berhasil dijalankan! Total entri: ' . count($pendidikans));
    }
}
