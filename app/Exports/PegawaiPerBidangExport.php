<?php

namespace App\Exports;

use App\Models\ModelBidang;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PegawaiPerBidangExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];

        $bidangs = ModelBidang::orderBy('bidang_nama')->get();

        foreach ($bidangs as $bidang) {
            $sheets[] = new PegawaiBidangSheet($bidang);
        }

        return $sheets;
    }
}