<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class UserCustomExport implements FromCollection, WithHeadings, WithStyles
{
    protected $data;

    public function collection()
    {
        $this->data = User::select(
            'user.user_nip',
            'user.user_nik',
            'user.user_nama',
            'sadarin_bidang.nama_bidang as bidang',
            'jabatan.nama_jabatan as jabatan',
            'golongan.nama_golongan as golongan',
            DB::raw("CASE WHEN user.user_tmt = '-' THEN 'Belum Melakukan' ELSE 'Sudah' END as status_pemuktahiran"),
            DB::raw("CASE WHEN user.user_foto = '-' THEN 'Belum Melakukan update foto' ELSE 'Sudah' END as status_foto"),
            DB::raw("CASE WHEN user.user_jabatan = '65' THEN 'Jabatan masih PPPK' ELSE 'Sudah' END as status_jabatan")
        )
            ->leftJoin('sadarin_bidang', 'user.user_bidang', '=', 'sadarin_bidang.id')
            ->leftJoin('jabatan', 'user.user_jabatan', '=', 'jabatan.id')
            ->leftJoin('golongan', 'user.user_golongan', '=', 'golongan.id')
            ->get();

        return $this->data;
    }

    public function headings(): array
    {
        return [
            'NIP',
            'NIK',
            'Nama',
            'Bidang',
            'Jabatan',
            'Golongan',
            'Status Pemuktahiran',
            'Status Foto',
            'Status Jabatan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $rowIndex = 2; // data mulai dari row ke-2
        $lastRow = count($this->data) + 1; // +1 untuk header

        // === Pewarnaan status ===
        foreach ($this->data as $row) {
            // Kolom G
            $sheet->getStyle("G{$rowIndex}")->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB(
                    $row->status_pemuktahiran === 'Sudah' ? 'CCFFCC' : 'FF9999'
                );

            // Kolom H
            $sheet->getStyle("H{$rowIndex}")->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB(
                    $row->status_foto === 'Sudah' ? 'CCFFCC' : 'FF9999'
                );

            // Kolom I
            $sheet->getStyle("I{$rowIndex}")->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB(
                    $row->status_jabatan === 'Sudah' ? 'CCFFCC' : 'FF9999'
                );

            $rowIndex++;
        }

        // === Heading style ===
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('DDDDDD');

        // === Border untuk semua cell ===
        $sheet->getStyle("A1:I{$lastRow}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('000000'));

        // === Auto-size kolom biar rapi ===
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }
}