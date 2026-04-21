<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB; // ⬅️ WAJIB
use Maatwebsite\Excel\Concerns\WithMultipleSheets; // ⬅️ WAJIB

class PegawaiRincianExport implements WithMultipleSheets
{
    protected $fields, $bidang, $jenis, $status, $groupBy;

    public function __construct($fields, $bidang, $jenis, $status, $groupBy)
    {
        $this->fields = $fields;
        $this->bidang = $bidang;
        $this->jenis  = $jenis;
        $this->status = $status;
        $this->groupBy = $groupBy;
    }

    public function sheets(): array
    {
        $sheets = [];

        // ========================
        // GROUP BY BIDANG
        // ========================
        if ($this->groupBy === 'bidang') {

            $bidangList = DB::table('sadarin_bidang')->get();

            foreach ($bidangList as $b) {
                $sheets[] = new PegawaiRincianPerSheetExport(
                    $this->fields,
                    [$b->bidang_id],
                    $this->jenis,
                    $this->status,
                    'BDG - ' . $b->bidang_nama
                );
            }
        }

        // ========================
        // GROUP BY JENIS
        // ========================
        elseif ($this->groupBy === 'jenis') {

            $map = [
                1 => 'PNS',
                2 => 'PPPK',
                3 => 'PPPK Paruh',
                4 => 'PJLP'
            ];

            foreach ($map as $id => $nama) {
                $sheets[] = new PegawaiRincianPerSheetExport(
                    $this->fields,
                    $this->bidang,
                    [$id],
                    $this->status,
                    $nama
                );
            }
        }

        // ========================
        // DEFAULT
        // ========================
        else {
            $sheets[] = new PegawaiRincianPerSheetExport(
                $this->fields,
                $this->bidang,
                $this->jenis,
                $this->status,
                'Semua Pegawai'
            );
        }

        return $sheets;
    }
}