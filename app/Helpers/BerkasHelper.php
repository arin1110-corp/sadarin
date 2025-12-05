<?php

if (!function_exists('cekBerkas')) {
    function cekBerkas($berkas, $jenis)
    {
        if (!$berkas) {
            return 'secondary'; // aman jika null
        }

        $data = $berkas->firstWhere('kumpulan_jenis', $jenis);

        if (!$data) {
            return 'secondary'; // belum ada data
        }

        return $data->kumpulan_status == 1 ? 'success' : 'secondary';
    }
}