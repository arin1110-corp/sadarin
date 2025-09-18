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

class PnsSheet implements FromCollection, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithTitle, WithStyles
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
            $user->user_nama,
            $user->jabatan_nama,
            $user->bidang_nama,
            $user->kumpulan_status == 1 ? 'Terkumpul' : 'Belum',
            $user->kumpulan_file ?? '-'
        ]);
    }

    public function headings(): array
    {
        return ['NIP', 'Nama', 'Jabatan', 'Bidang', 'Status Kumpul', 'Link File'];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // NIP jadi teks
        ];
    }

    public function title(): string
    {
        return 'PNS';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Border semua sel
        $sheet->getStyle('A1:F' . ($this->data->count() + 1))
            ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }
}
