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
                WHEN sadarin_user.user_tmt = '1990-01-01' THEN sadarin_user.user_tmt
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

            DB::raw("CASE WHEN sadarin_user.user_tgllahir='1990-01-01' then sadarin_user.user_tgllahir
            else 'Sudah'
            END as tanggal_lahir"),

            DB::raw("CASE
                WHEN sadarin_user.user_pendidikan = '0' THEN 'Belum Update'
                ELSE 'Sudah'
            END as pendidikan"),

            DB::raw("CASE
                WHEN sadarin_user.user_norek = '-' THEN 'Belum Update'
                ELSE 'Sudah'
            END as nomor_rekening"),

            DB::raw("CASE
                WHEN sadarin_user.user_npwp = '-' THEN 'Belum Update'
                ELSE 'Sudah'
            END as npwp"),

            DB::raw("CASE
                WHEN sadarin_user.user_notelp = '-' THEN 'Belum Update'
                ELSE 'Sudah'
            END as nomor_hp"),

            DB::raw("CASE
                WHEN sadarin_user.user_email = '-' THEN 'Belum Update'
                ELSE 'Sudah'
            END as email"),
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
        return ['NIP', 'NIK', 'Nama', 'Jenis Kerja', 'Bidang', 'Jabatan', 'Golongan', 'Status TMT', 'Status Foto', 'Status Jabatan', 'Tanggal Lahir', 'Pendidikan', 'Nomor Rekening', 'NPWP', 'Nomor HP', 'Email'];
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
                ->setRGB($row->jabatan === 'PPPK' ? 'FF0000' : 'FFFFFF');
            // H - Pemuktahiran
            $sheet
                ->getStyle("H{$rowIndex}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($row->status_pemuktahiran === '1990-01-01' ? 'FF0000' : 'CCFFCC');

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
                ->setRGB($row->status_jabatan === 'Sudah' ? 'CCFFCC' : 'FF0000');

            // K ===== TANGGAL LHR =====
            $sheet
                ->getStyle("K{$rowIndex}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($row->tanggal_lahir === '1990-01-01' ? 'FF0000' : 'CCFFCC');

            // L ===== Pendidikan =====
            $sheet
                ->getStyle("L{$rowIndex}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($row->pendidikan === 'Belum Update' ? 'FF0000' : 'CCFFCC');
            // M ===== Nomor Rekening =====
            $sheet
                ->getStyle("M{$rowIndex}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($row->nomor_rekening === 'Belum Update' ? 'FF0000' : 'CCFFCC');
            // N ===== NPWP =====
            $sheet
                ->getStyle("N{$rowIndex}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($row->npwp === 'Belum Update' ? 'FF0000' : 'CCFFCC');
            // O ===== Nomor HP =====
            $sheet
                ->getStyle("O{$rowIndex}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($row->nomor_hp === 'Belum Update' ? 'FF0000' : 'CCFFCC');
            // P ===== Email =====
            $sheet
                ->getStyle("P{$rowIndex}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB($row->email === 'Belum Update' ? 'FF0000' : 'CCFFCC');


            $rowIndex++;
        }

        // ===== HEADER =====
        $sheet->getStyle('A1:P1')->getFont()->setBold(true);
        $sheet->getStyle('A1:P1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('DDDDDD');

        // ===== BORDER =====
        $sheet
            ->getStyle("A1:P{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000000'));

        // ===== AUTOSIZE =====
        foreach (range('A', 'P') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }
}