<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\{
    FromCollection, WithHeadings, WithTitle, WithMapping,
    ShouldAutoSize, WithStyles, WithEvents, WithColumnFormatting
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Events\AfterSheet;

class PegawaiRincianPerSheetExport implements 
    FromCollection, WithHeadings, WithTitle, WithMapping,
    ShouldAutoSize, WithStyles, WithEvents, WithColumnFormatting
{
    protected $fields, $bidang, $jenis, $status, $title;

    public function __construct($fields, $bidang, $jenis, $status, $title)
    {
        $this->fields = $fields;
        $this->bidang = $bidang;
        $this->jenis  = $jenis;
        $this->status = $status;
        $this->title  = $title;
    }

    public function collection()
    {
        $query = DB::table('sadarin_user')
            ->leftJoin('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->leftJoin('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
            ->leftJoin('sadarin_pendidikan', 'sadarin_user.user_pendidikan', '=', 'sadarin_pendidikan.pendidikan_id')
            ->leftJoin('sadarin_eselon', 'sadarin_user.user_eselon', '=', 'sadarin_eselon.eselon_id')
            ->leftJoin('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id');

        if (!empty($this->bidang)) {
            $query->whereIn('sadarin_user.user_bidang', $this->bidang);
        }

        if (!empty($this->jenis)) {
            $query->whereIn('sadarin_user.user_jeniskerja', $this->jenis);
        }

        if (!empty($this->status)) {
            $query->whereIn('sadarin_user.user_status', $this->status);
        }

        return $query->get();
    }

    // 🔥 FORMAT DATA (ANTI E+)
    public function map($row): array
    {
        $result = [];

        foreach ($this->fields as $f) {
            switch ($f) {

                // ⛔ FORCE TEXT (DOUBLE PROTECTION)
                case 'user_nip':
                case 'user_nik':
                case 'user_notelp':
                case 'user_norek':
                case 'user_npwp':
                case 'user_bpjs':
                    $result[] = "'" . ($row->$f ?? '');
                    break;

                case 'user_pendidikan':
                    $result[] = ($row->pendidikan_jenjang ?? '-') . ' - ' . ($row->pendidikan_jurusan ?? '-');
                    break;

                case 'user_golongan':
                    $result[] = ($row->golongan_nama ?? '-') . ' (' . ($row->golongan_pangkat ?? '-') . ')';
                    break;

                case 'user_eselon':
                    $result[] = $row->eselon_nama ?? '-';
                    break;

                case 'bidang_nama':
                    $result[] = $row->bidang_nama ?? '-';
                    break;

                case 'jabatan_nama':
                    $result[] = $row->jabatan_nama ?? '-';
                    break;

                case 'user_jeniskerja':
                    $map = [1=>'PNS',2=>'PPPK',3=>'PPPK Paruh Waktu',4=>'PJLP'];
                    $result[] = $map[$row->user_jeniskerja] ?? '-';
                    break;

                case 'user_status':
                    $result[] = $row->user_status == 1 ? 'Aktif' : 'Tidak Aktif';
                    break;

                default:
                    $result[] = $row->$f ?? '-';
                    break;
            }
        }

        return $result;
    }

    // 🔥 FORMAT KOLOM EXCEL (ANTI E+ LEVEL DEWA)
    public function columnFormats(): array
    {
        $formats = [];

        foreach ($this->fields as $index => $field) {

            $col = Coordinate::stringFromColumnIndex($index + 1);

            if (in_array($field, [
                'user_nip',
                'user_nik',
                'user_notelp',
                'user_norek',
                'user_npwp',
                'user_bpjs'
            ])) {
                $formats[$col] = NumberFormat::FORMAT_TEXT;
            }
        }

        return $formats;
    }

    // 🔥 HEADER RAPI
    public function headings(): array
    {
        $map = [
            'user_nip' => 'NIP',
            'user_nik' => 'NIK',
            'user_nama' => 'Nama',
            'user_jk' => 'Jenis Kelamin',
            'user_tgllahir' => 'Tanggal Lahir',
            'user_tempatlahir' => 'Tempat Lahir',
            'user_alamat' => 'Alamat',
            'user_email' => 'Email',
            'user_notelp' => 'No HP',
            'user_jeniskerja' => 'Jenis Kerja',
            'jabatan_nama' => 'Jabatan',
            'user_eselon' => 'Eselon',
            'user_kelasjabatan' => 'Kelas Jabatan',
            'user_golongan' => 'Golongan',
            'bidang_nama' => 'Bidang',
            'user_lokasikerja' => 'Lokasi Kerja',
            'user_tmt' => 'TMT',
            'user_spmt' => 'SPMT',
            'user_status' => 'Status',
        ];

        return array_map(fn($f) => $map[$f] ?? strtoupper($f), $this->fields);
    }

    // 🔥 STYLE
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center']
            ]
        ];
    }

    // 🔥 EVENT
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function($event) {

                $sheet = $event->sheet->getDelegate();

                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();

                // Freeze header
                $sheet->freezePane('A2');

                // Header warna
                $sheet->getStyle("A1:{$highestColumn}1")->applyFromArray([
                    'fill' => [
                        'fillType' => 'solid',
                        'color' => ['rgb' => 'D9E1F2']
                    ],
                ]);

                // Border
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => 'thin',
                            ],
                        ],
                    ]);
            }
        ];
    }

    // 🔥 TITLE
    public function title(): string
    {
        $name = preg_replace('/[\\\\\\/\\?\\*\\[\\]:]/', '', $this->title);
        return substr($name, 0, 31);
    }
}