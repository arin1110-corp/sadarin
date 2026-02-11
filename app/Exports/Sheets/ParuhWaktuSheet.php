<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ParuhWaktuSheet implements FromCollection, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data)->map(fn($user) => [
            "'" . (string)$user->user_nip,
            "'" . (string)$user->user_nik,
            $user->user_nama,
            $user->jabatan_nama,
            $user->bidang_nama,
            $user->kumpulan_status == 1 ? 'Terkumpul' : 'Belum',
            $user->kumpulan_file ?? '-',
            $user->kumpulan_keterangan
        ]);
    }

    public function headings(): array
    {
        return ['NIP', 'NIK', 'Nama', 'Jabatan', 'Bidang', 'Status Kumpul', 'Link File', 'Keterangan'];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // NIP jadi teks
            'B' => NumberFormat::FORMAT_TEXT, // NIK jadi teks
        ];
    }

    public function title(): string
    {
        return 'Paruh Waktu';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Border semua sel
        $sheet->getStyle('A1:F' . ($this->data->count() + 1))
            ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }
}