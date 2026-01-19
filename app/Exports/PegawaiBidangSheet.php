<?php

namespace App\Exports;

use App\Models\ModelUser as User;
use App\Models\ModelBidang as Bidang;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithStyles, WithTitle};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class PegawaiBidangSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $bidang;
    protected $data;

    public function __construct(Bidang $bidang)
    {
        $this->bidang = $bidang;
    }

    public function collection()
    {
        $this->data = User::select(
            DB::raw("CONCAT(\"'\", sadarin_user.user_nip) as user_nip"),
            DB::raw("CONCAT(\"'\", sadarin_user.user_nik) as user_nik"),
            'sadarin_user.user_nama',

            // 👉 JENIS KERJA (PENTING)
            DB::raw("CASE
                WHEN sadarin_user.user_jeniskerja = 1 THEN 'PNS'
                WHEN sadarin_user.user_jeniskerja = 2 THEN 'PPPK'
                WHEN sadarin_user.user_jeniskerja = 3 THEN 'PPPK Paruh Waktu'
                WHEN sadarin_user.user_jeniskerja = 4 THEN 'PJLP'
                ELSE '-'
            END as jenis_kerja"),

                    'sadarin_bidang.bidang_nama as bidang',
                    'sadarin_jabatan.jabatan_nama as jabatan',
                    'sadarin_golongan.golongan_nama as golongan',

            DB::raw("CASE
                WHEN sadarin_user.user_tmt = '1990-01-01' THEN 'Belum Melakukan'
                ELSE 'Sudah'
            END as status_pemuktahiran"),

            DB::raw("CASE
                WHEN sadarin_user.user_foto = '-' THEN 'Belum Upload Foto'
                ELSE 'Sudah'
            END as status_foto"),

            DB::raw("CASE
                WHEN sadarin_user.user_jabatan = '65' THEN 'Jabatan masih PPPK'
                ELSE 'Sudah'
            END as status_jabatan"),
            )

            ->leftJoin('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
            ->leftJoin('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->leftJoin('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')
            ->where('sadarin_user.user_bidang', $this->bidang->bidang_id)
            ->where('sadarin_user.user_status', 1)
            ->orderBy('sadarin_user.user_nama')
            ->get();

        return $this->data;
    }

    public function headings(): array
    {
        return ['NIP', 'NIK', 'Nama', 'Jenis Kerja', 'Bidang', 'Jabatan', 'Golongan', 'Status Pemuktahiran', 'Status Foto', 'Status Jabatan'];
    }

    public function title(): string
    {
        return $this->bidang->bidang_nama;
    }

    public function styles(Worksheet $sheet)
    {
        $rowIndex = 2;
        $lastRow = count($this->data) + 1;

        // ===== WARNA STATUS =====
        foreach ($this->data as $row) {
            // D - Jenis Kerja
            $sheet
                ->getStyle("D{$rowIndex}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB(match ($row->jenis_kerja) {
                    'PNS' => 'CCFFCC',
                    'PPPK' => 'CCCCFF',
                    'PPPK Paruh Waktu' => 'FFFF99',
                    'PJLP' => 'FFCC99',
                    default => 'FFFFFF',
                });
            // F - Jabatan
            $sheet
                ->getStyle("F{$rowIndex}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($row->jabatan === 'PPPK' ? 'FFCC99' : 'FFFFFF');
            // H - Pemuktahiran
            $sheet
                ->getStyle("H{$rowIndex}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($row->status_pemuktahiran === 'Sudah' ? 'CCFFCC' : 'FF9999');

            // I - Foto
            $sheet
                ->getStyle("I{$rowIndex}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($row->status_foto === 'Sudah' ? 'CCFFCC' : 'FF0000');

            // J - Jabatan
            $sheet
                ->getStyle("J{$rowIndex}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($row->status_jabatan === 'Sudah' ? 'CCFFCC' : 'FF9999');

            $rowIndex++;
        }

        // ===== HEADER =====
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('DDDDDD');

        // ===== BORDER =====
        $sheet
            ->getStyle("A1:I{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000000'));

        // ===== AUTOSIZE =====
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }
}