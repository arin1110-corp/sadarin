<!DOCTYPE html>
<html lang="id">

<head>
    <title>SADARIN - Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/image/pemprov.png') }}">

    <style>
        body {
            background: #f4f6f9;
            font-family: Poppins, sans-serif;
        }

        .in {
            color: #ff6600;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .08);
        }
    </style>
</head>

<body>
    <div class="container py-5">

        {{-- HEADER --}}
        <div class="text-center mb-4">
            <img src="{{ asset('assets/image/pemprov.png') }}" height="80">
            <h1 class="fw-bold text-secondary mt-2">
                SADAR<span class="in">IN</span>
            </h1>
            <p class="text-muted">
                Sistem Aplikasi Data dan Arsip Internal – Dinas Kebudayaan Provinsi Bali
            </p>
        </div>

        {{-- STATISTIK --}}
        <div class="row g-3 mb-5">
            <div class="col-md-4">
                <div class="card p-3 text-center">
                    <i class="bi bi-people fs-1"></i>
                    <h5>Total Pegawai</h5>
                    <strong>{{ $totalPegawai }} Orang</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 text-center">
                    <i class="bi bi-gender-male fs-1"></i>
                    <h5>Laki-laki</h5>
                    <strong>{{ $jumlahLaki }} Orang</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 text-center">
                    <i class="bi bi-gender-female fs-1"></i>
                    <h5>Perempuan</h5>
                    <strong>{{ $jumlahPerempuan }} Orang</strong>
                </div>
            </div>
        </div>

        @php
            function jk($v)
            {
                return $v == 'L' ? 'Laki-laki' : 'Perempuan';
            }
        @endphp

        {{-- LOOP SEMUA REKAP UMUM --}}
        @foreach ([
        'jenis_kerja' => 'Jenis Kerja',
        'jabatan' => 'Jabatan',
        'golongan' => 'Golongan',
        'eselon' => 'Eselon',
        'kategori_jabatan' => 'Kategori Jabatan',
        'pendidikan' => 'Pendidikan',
    ] as $key => $judul)
            <h4 class="mt-5">Rekap Pegawai per {{ $judul }}</h4>
            <div class="row g-3">
                @forelse($dataRekap[$key] as $row)
                    <div class="col-md-3">
                        <div class="card p-3">
                            <div class="fw-bold">{{ $row->nama ?? '-' }}</div>
                            <div>{{ $row->jumlah }} Orang</div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Data tidak tersedia</p>
                @endforelse
            </div>
        @endforeach

        {{-- 🔥 JABATAN → JENIS KELAMIN --}}
        <h4 class="mt-5">Rekap Pegawai per Jabatan & Jenis Kelamin</h4>

        @php
            $jabatanJK = $dataRekap['jabatan_jk']->groupBy('jabatan');
        @endphp

        @foreach ($jabatanJK as $jabatan => $items)
            <div class="card mb-3">
                <div class="card-header fw-bold">
                    {{ $jabatan }}
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        @foreach ($items as $row)
                            <li>{{ jk($row->jk) }} : {{ $row->jumlah }} Orang</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach

    </div>
</body>

</html>
