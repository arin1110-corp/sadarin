<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ModelUser;

class ConvertNamaPegawai extends Command
{
    protected $signature = 'pegawai:convert-nama-dinamis';
    protected $description = 'Convert nama lengkap pegawai menjadi gelar depan dan belakang, simpan nama UPPERCASE, trim spasi';

    public function handle()
    {
        $users = ModelUser::all();

        foreach ($users as $user) {
            $fullName = trim($user->user_nama);

            $gelarDepan = '';
            $gelarBelakang = '';
            $nama = $fullName;

            // Pisahkan gelar belakang (misal setelah koma)
            if (str_contains($fullName, ',')) {
                $parts = explode(',', $fullName);
                $nama = trim($parts[0]);
                $gelarBelakang = trim($parts[1]);
            }

            // Pisahkan gelar depan (kata pertama sebelum spasi jika mengandung titik)
            $kata = explode(' ', $nama);
            if (isset($kata[0]) && str_contains($kata[0], '.')) {
                $gelarDepan = trim($kata[0]);
                array_shift($kata);
                $nama = implode(' ', $kata);
            }

            // Trim semua spasi dan UPPERCASE untuk nama inti
            $gelarDepan = trim($gelarDepan);
            $nama = strtoupper(trim($nama));
            $gelarBelakang = trim($gelarBelakang);

            // Update database
            $user->user_gelardepan = $gelarDepan ?: '-';
            $user->user_gelarbelakang = $gelarBelakang ?: '-';
            $user->user_nama = $nama;
            $user->save();

            $this->info("Updated: $fullName => Depan: $gelarDepan | Nama: $nama | Belakang: $gelarBelakang");
        }

        $this->info('Konversi semua pegawai selesai!');
    }
}
