<!DOCTYPE html>
<html lang="id">

<head>
    @include('kepegawaian.partials.headkepegawaian')

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
</head>

<body class="bg-light">

    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            @include('kepegawaian.partials.sidebarkepegawaian')

            {{-- Konten Utama --}}
            <main class="col-md-10 ms-sm-auto p-4">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h2 class="fw-bold text-primary">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </h2>
                    <div class="d-flex align-items-center gap-3">
                        <input class="form-control form-control-sm" type="text" placeholder="🔍 Cari data...">
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-4 me-2 text-primary"></i>
                                <span class="fw-semibold">Admin</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profil</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="bi bi-box-arrow-right me-2"></i> Keluar</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Judul Statistik --}}
                <div class="mb-4">
                    <h3 class="fw-bold text-center text-secondary">
                        <i class="bi bi-bar-chart me-2"></i> Jumlah Pensiun Tahun 2025
                    </h3>
                </div>

                {{-- Statistik Bulanan --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-2">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar-event text-danger fs-2 mb-2"></i>
                                <h6 class="fw-semibold">Bulan {{ \Carbon\Carbon::now()->translatedFormat('F') }}</h6>
                                <p class="display-6 fw-bold text-danger">{{ $pensiunBulanIni }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar-event text-warning fs-2 mb-2"></i>
                                <h6 class="fw-semibold">Bulan
                                    {{ \Carbon\Carbon::now()->addMonth()->translatedFormat('F') }}
                                </h6>
                                <p class="display-6 fw-bold text-warning">{{ $pensiunBulanDepan }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar3 text-primary fs-2 mb-2"></i>
                                <h6 class="fw-semibold">3 Bulan</h6>
                                <p class="display-6 fw-bold text-primary">{{ $pensiun3Bulan }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar3 text-info fs-2 mb-2"></i>
                                <h6 class="fw-semibold">6 Bulan</h6>
                                <p class="display-6 fw-bold text-info">{{ $pensiun6Bulan }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card shadow-sm border-0 bg-danger text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar4-week fs-2 mb-2"></i>
                                <h6 class="fw-semibold">Tahun 2025</h6>
                                <p class="display-6 fw-bold">{{ $pensiun2025 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card shadow-sm border-0 bg-dark text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar4-range fs-2 mb-2"></i>
                                <h6 class="fw-semibold">Tahun 2026</h6>
                                <p class="display-6 fw-bold">{{ $pensiun2026 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Statistik PNS & PPPK --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-person-badge text-success fs-2 mb-2"></i>
                                <h6 class="fw-semibold">PNS Pensiun Tahun Ini</h6>
                                <p class="display-5 fw-bold text-success">{{ $pnsTahunIni }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-person-workspace text-primary fs-2 mb-2"></i>
                                <h6 class="fw-semibold">PPPK Pensiun Tahun Ini</h6>
                                <p class="display-5 fw-bold text-primary">{{ $pppkTahunIni }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Daftar Pegawai Pensiun --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-people-fill me-2"></i>Daftar Pegawai Pensiun 1 Tahun ke Depan
                        </h5>
                        <table id="tablePensiun" class="table table-striped table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Jenis Kerja</th>
                                    <th>Tanggal Pensiun</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($daftarPensiun as $pegawai)
                                <tr>
                                    <td>{{ $pegawai->user_nama }}</td>
                                    <td>{{ $pegawai->user_jabatan }}</td>
                                    <td>{{ $pegawai->user_jeniskerja == '1' ? 'PNS' : ($pegawai->user_jeniskerja == '2' ? 'PPPK' : '-') }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($pegawai->tanggal_pensiun)->translatedFormat('d F Y') }}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#modalDetailPensiun{{ $pegawai->user_id }}">
                                            <i class="bi bi-eye"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Modals dipisah di luar tabel --}}
                @foreach ($daftarPensiun as $pegawai)
                <div class="modal fade" id="modalDetailPensiun{{ $pegawai->user_id }}" tabindex="-1"
                    aria-labelledby="modalDetailPensiunLabel{{ $pegawai->user_id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="modalDetailPensiunLabel{{ $pegawai->user_id }}">Detail
                                    Pegawai</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-4 text-center">
                                        <img src="{{ ($pegawai->user_foto && $pegawai->user_foto != '-') ? asset($pegawai->user_foto) : asset('assets/image/pemprov.png') }}"
                                            alt="Foto Pegawai" class="img-thumbnail rounded shadow-sm" width="384px"
                                            height="auto">
                                    </div>
                                    <div class="col-md-8">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th colspan="2" class="text-center">*** IDENTITAS PEGAWAI ***</th>
                                            </tr>
                                            <tr>
                                                <th width="30%">NIP</th>
                                                <td>: {{ $pegawai->user_nip }}</td>
                                            </tr>
                                            <tr>
                                                <th>NIK</th>
                                                <td>: {{ $pegawai->user_nik ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nama</th>
                                                <td>: {{ $pegawai->user_nama }}</td>
                                            </tr>
                                            <tr>
                                                <th>Gelar Depan</th>
                                                <td>: {{ $pegawai->user_gelardepan ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Gelar Belakang</th>
                                                <td>: {{ $pegawai->user_gelarbelakang ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jenis Kelamin</th>
                                                <td>:
                                                    {{ $pegawai->user_jk == 'L' ? 'Laki-laki' : ($pegawai->user_jk == 'P' ? 'Perempuan' : '-') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tempat dan Tanggal Lahir</th>
                                                <td>:
                                                    {{ $pegawai->user_tempatlahir ?? '-' }},
                                                    {{ $pegawai->user_tgllahir ? \Carbon\Carbon::parse($pegawai->user_tgllahir)->translatedFormat('j F Y') : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Pendidikan</th>
                                                <td>:
                                                    {{ $pegawai->pendidikan_jenjang ?? ($pegawai->pendidikan->pendidikan_jurusan ?? '-') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="2" class="text-center">*** IDENTITAS JABATAN ***</th>
                                            </tr>
                                            <tr>
                                                <th>Jabatan</th>
                                                <td>: {{ $pegawai->user_jabatan }}</td>
                                            </tr>
                                            <tr>
                                                <th>Golongan</th>
                                                <td>:
                                                    {{ $pegawai->golongan_nama.' - '.$pegawai->golongan_pangkat ?? '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Eselon</th>
                                                <td>: {{ $pegawai->user_eselon ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kelas Jabatan</th>
                                                <td>: Kelas Jabatan {{ $pegawai->user_kelasjabatan ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Unit Kerja</th>
                                                <td>: {{ $pegawai->bidang_nama ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>TMT</th>
                                                <td>:
                                                    {{ $pegawai->user_tmt ? \Carbon\Carbon::parse($pegawai->user_tmt)->translatedFormat('j F Y') : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>SPMT</th>
                                                <td>:
                                                    {{ $pegawai->user_spmt ? \Carbon\Carbon::parse($pegawai->user_spmt)->translatedFormat('j F Y') : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Jenis Kerja</th>
                                                <td>:
                                                    {{ $pegawai->user_jeniskerja == '1' ? 'PNS' : ($pegawai->user_jeniskerja == '2' ? 'PPPK' : '-') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Pensiun</th>
                                                <td>:
                                                    {{ \Carbon\Carbon::parse($pegawai->tanggal_pensiun)->translatedFormat('d F Y') }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th colspan="2" class="text-center">*** INFORMASI KONTAK ***</th>
                                            </tr>
                                            <tr>
                                                <th>Alamat</th>
                                                <td>: {{ $pegawai->user_alamat ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>No. Telepon</th>
                                                <td>: {{ $pegawai->user_notelp ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>: {{ $pegawai->user_email ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>BPJS</th>
                                                <td>: {{ $pegawai->user_bpjs ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>No. Rekening</th>
                                                <td>: {{ $pegawai->user_norek ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>NPWP</th>
                                                <td>: {{ $pegawai->user_npwp ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jumlah Tanggungan</th>
                                                <td>: {{ $pegawai->user_jmltanggungan ?? '-' }} Orang</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>:
                                                    @if($pegawai->user_status == '1')
                                                    <span class="badge bg-success">Aktif</span>
                                                    @else
                                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach




                {{-- Footer --}}
                @include('kepegawaian.partials.footerkepegawaian')
            </main>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tableAll').DataTable();
            $('#tablePns').DataTable();
            $('#tablePppk').DataTable();
        });
    </script>
</body>

</html>