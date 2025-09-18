<?php

namespace App\Exports;

use App\Exports\Sheets\PnsSheet;
use App\Exports\Sheets\PppkSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PegawaiExport implements WithMultipleSheets
{
    protected $dataPns;
    protected $dataPppk;

    public function __construct($dataPns, $dataPppk)
    {
        $this->dataPns = $dataPns;
        $this->dataPppk = $dataPppk;
    }

    public function sheets(): array
    {
        return [
            new PnsSheet($this->dataPns),
            new PppkSheet($this->dataPppk),
        ];
    }
}
