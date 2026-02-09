<!DOCTYPE html>
<html lang="id">
<head>
    <title>SADARIN - Rekap Pegawai per Bidang</title>
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
            Rekapitulasi Pegawai per Bidang<br>
            Dinas Kebudayaan Provinsi Bali
        </p>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <i class="bi bi-people fs-1"></i>
                <h6>Total Pegawai</h6>
                <strong>{{ $totalPegawai }} Orang</strong>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <i class="bi bi-gender-male fs-1"></i>
                <h6>Laki-laki</h6>
                <strong>{{ $jumlahLaki }} Orang</strong>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <i class="bi bi-gender-female fs-1"></i>
                <h6>Perempuan</h6>
                <strong>{{ $jumlahPerempuan }} Orang</strong>
            </div>
        </div>
    </div>

    {{-- LOOP PER BIDANG --}}
    @forelse($bidangRekap as $bidang => $detail)

        <div class="card mb-5">
            <div class="card-header fw-bold bg-secondary text-white">
                {{ $bidang }}
            </div>

            <div class="card-body">

                {{-- ================= PENDIDIKAN TERAKHIR ================= --}}
                <h5 class="mb-3">Pendidikan Terakhir</h5>
                <table class="table table-bordered table-sm mb-4">
                    <thead class="table-light">
                        <tr>
                            <th>Jenjang</th>
                            <th width="120">L</th>
                            <th width="120">P</th>
                            <th width="120">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detail['pendidikan_jenjang'] ?? [] as $jenjang => $jk)
                        <tr>
                            <td>{{ $jenjang }}</td>
                            <td>{{ $jk['L'] ?? 0 }}</td>
                            <td>{{ $jk['P'] ?? 0 }}</td>
                            <td>{{ ($jk['L'] ?? 0) + ($jk['P'] ?? 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- ================= KATEGORI JABATAN ================= --}}
                <h5 class="mb-3">Rekap per Kategori Jabatan</h5>
                <table class="table table-bordered table-sm mb-4">
                    <thead class="table-light">
                        <tr>
                            <th>Kategori Jabatan</th>
                            <th width="120">L</th>
                            <th width="120">P</th>
                            <th width="120">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detail['jabatan_kategori'] ?? [] as $kategori => $jk)
                        <tr>
                            <td>{{ $kategori }}</td>
                            <td>{{ $jk['L'] ?? 0 }} Orang</td>
                            <td>{{ $jk['P'] ?? 0 }} Orang</td>
                            <td>{{ ($jk['L'] ?? 0) + ($jk['P'] ?? 0) }} Orang</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- ================= JABATAN ================= --}}
                <h5 class="mb-3">Rekap per Jabatan</h5>
                <table class="table table-bordered table-sm mb-4">
                    <thead class="table-light">
                        <tr>
                            <th>Jabatan</th>
                            <th width="120">L</th>
                            <th width="120">P</th>
                            <th width="120">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detail['jabatan'] ?? [] as $nama => $jk)
                        <tr>
                            <td>{{ $nama }}</td>
                            <td>{{ $jk['L'] ?? 0 }}</td>
                            <td>{{ $jk['P'] ?? 0 }}</td>
                            <td>{{ ($jk['L'] ?? 0) + ($jk['P'] ?? 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- ================= GOLONGAN ================= --}}
                <h5 class="mb-3">Rekap per Golongan</h5>
                <table class="table table-bordered table-sm mb-4">
                    <thead class="table-light">
                        <tr>
                            <th>Golongan</th>
                            <th width="120">L</th>
                            <th width="120">P</th>
                            <th width="120">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detail['golongan'] ?? [] as $nama => $jk)
                        <tr>
                            <td>{{ $nama ?? '-' }}</td>
                            <td>{{ $jk['L'] ?? 0 }}</td>
                            <td>{{ $jk['P'] ?? 0 }}</td>
                            <td>{{ ($jk['L'] ?? 0) + ($jk['P'] ?? 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- ================= DETAIL PENDIDIKAN ================= --}}
                <h5 class="mb-3">Detail Pendidikan (Jenjang - Jurusan)</h5>
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Pendidikan</th>
                            <th width="120">L</th>
                            <th width="120">P</th>
                            <th width="120">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detail['pendidikan_detail'] ?? [] as $nama => $jk)
                        <tr>
                            <td>{{ $nama }}</td>
                            <td>{{ $jk['L'] ?? 0 }}</td>
                            <td>{{ $jk['P'] ?? 0 }}</td>
                            <td>{{ ($jk['L'] ?? 0) + ($jk['P'] ?? 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    @empty
        <div class="alert alert-warning text-center">
            Data rekap per bidang belum tersedia
        </div>
    @endforelse

</div>
</body>
</html>
