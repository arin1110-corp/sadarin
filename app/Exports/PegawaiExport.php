<?php

namespace App\Exports;

use App\Exports\Sheets\PnsSheet;
use App\Exports\Sheets\PppkSheet;
use App\Exports\Sheets\PJLPSheet;
use App\Exports\Sheets\ParuhWaktuSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PegawaiExport implements WithMultipleSheets
{
    protected $dataPns;
    protected $dataPppk;
    protected $dataPJLP;
    protected $dataParuhWaktu;

    public function __construct($dataPns, $dataPppk, $dataPJLP, $dataParuhWaktu)
    {
        $this->dataPns = $dataPns;
        $this->dataPppk = $dataPppk;
        $this->dataPJLP = $dataPJLP;
        $this->dataParuhWaktu = $dataParuhWaktu;
    }

    public function sheets(): array
    {
        return [
            new PnsSheet($this->dataPns),
            new PppkSheet($this->dataPppk),
            new ParuhWaktuSheet($this->dataParuhWaktu),
            new PJLPSheet($this->dataPJLP),
        ];
    }
}