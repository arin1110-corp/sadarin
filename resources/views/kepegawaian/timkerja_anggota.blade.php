<!DOCTYPE html>
<html lang="id">

<head>
    @include('kepegawaian.partials.headkepegawaian')

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                        <div class="dropdown">
                            <a href="#"
                                class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-4 me-2 text-primary"></i>
                                <span class="fw-semibold">Admin</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profil</a>
                                </li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="bi bi-box-arrow-right me-2"></i> Keluar</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-briefcase me-2"></i> Data Tim Kerja</h5>
                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalTim">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Data
                        </button>
                    </div>
                    {{-- notifikasi --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    {{-- end notifikasi --}}
                </div>

                <div style="font-size: 25px; color: #999;">
                    <strong>{{ $timkerja->timkerja_nama }}</strong>
                </div>

                <!-- Kartu Tim Kerja -->
                <div class="org-chart">
                    <div class="org-node p-4 shadow-sm">
                        <!-- Nama Bidang -->
                        <h5 style="color: #020202; font-weight: 600;">Bidang: {{ $timkerja_data->bidang_nama ?? '-' }}
                        </h5>

                        <!-- Ketua Tim -->
                        <div class="d-flex flex-column align-items-center mt-3">
                            @if (!empty($timkerja_data->foto_ketua))
                                <img src="{{ asset($timkerja_data->foto_ketua) }}" alt="Foto Ketua" class="rounded mb-2"
                                    style="width:100px; height:120px; object-fit:cover; border:2px solid #666;">
                            @else
                                <div class="bg-secondary mb-2"
                                    style="width:100px; height:120px; display:flex; align-items:center; justify-content:center; color:white;">
                                    No Image</div>
                            @endif
                            <h6 class="mb-1">{{ $timkerja_data->ketua_tim ?? '-' }}</h6>
                            <small class="text-muted">Ketua Tim</small>
                        </div>

                        <!-- Uraian Tim -->
                        <div class="text-start mt-4" style="width: 100%;">
                            <h6>Uraian Tim:</h6>
                            @if (!empty($timkerja_data->timkerja_uraian))
                                @php
                                    // Pisahkan uraian berdasarkan pembatas unik |||
                                    $uraianList = explode('|||', $timkerja_data->timkerja_uraian);
                                @endphp
                                <ol>
                                    @foreach ($uraianList as $item)
                                        <li>{{ trim($item) }}</li>
                                    @endforeach
                                </ol>
                            @else
                                <p class="text-muted">Uraian belum tersedia.</p>
                            @endif
                            <div class="text-end mt-3">
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalUraian">
                                    <i class="bi bi-pencil-square"></i> Ubah Uraian
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="org-chart">
                    <div class="org-node p-4">
                        <!-- Daftar Anggota Tim -->
                        <div class="text-start mt-4" style="width: 100%;">
                            <h6 class="mb-4">Anggota Tim:</h6>

                            @if ($anggota->isNotEmpty())
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                                    @foreach ($anggota as $a)
                                        <div class="col">
                                            <div class="card text-center h-100 border-0">
                                                @if (!empty($a->user_foto))
                                                    <center>
                                                        <img src="{{ $a->user_foto && $a->user_foto != '-' ? asset($a->user_foto) : asset('assets/image/pemprov.png') }}"
                                                            class="card-img-top"
                                                            style="width: 100px; height: 200px; object-fit: cover;"
                                                            alt="Foto {{ $a->user_nama }}">
                                                    </center>
                                                @else
                                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                                                        style="height: 200px;">
                                                        No Image
                                                    </div>
                                                @endif
                                                <div class="card-body p-2">
                                                    <h6 class="card-title mb-0">{{ $a->user_nama ?? 'Tidak ada nama' }}
                                                    </h6>
                                                    <small class="text-muted">{{ $a->jabatan_nama ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">Belum ada anggota tim.</p>
                            @endif

                            <div class="text-end mt-3">
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalTambahAnggota">
                                    <i class="bi bi-person-plus"></i> Tambah Anggota
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="modalTambahAnggota" tabindex="-1" aria-labelledby="modalTambahAnggotaLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahAnggotaLabel">Tambah Anggota Tim</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <table class="table table-bordered table-hover text-start" id="tablePegawaiModal">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>Bidang</th>
                                            <th>Tim Kerja</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $u)
                                            <tr>
                                                <td>
                                                    <img src="{{ $u->user_foto && $u->user_foto != '-' ? asset($u->user_foto) : asset('assets/image/pemprov.png') }}"
                                                        alt="Foto {{ $u->user_nama }}" class="rounded"
                                                        style="width: 50px; object-fit: cover;">
                                                </td>
                                                <td>{{ $u->user_nama }}</td>
                                                <td>{{ $u->jabatan_nama ?? '-' }}</td>
                                                <td>{{ $u->bidang_nama ?? '-' }}</td>
                                                <td>
                                                    {{ $anggotaMapping[$u->user_id] ?? 'Belum Ada Tim Kerja' }}
                                                </td>
                                                <td>
                                                    @if (in_array($u->user_id, $anggotaIds))
                                                        <button class="btn btn-secondary btn-sm" disabled>Sudah di Tim
                                                            Ini</button>
                                                    @elseif(isset($anggotaMapping[$u->user_id]))
                                                        <button class="btn btn-warning btn-sm" disabled>Sudah di Tim
                                                            Lain</button>
                                                    @else
                                                        <button class="btn btn-sm btn-primary pilihPegawaiBtn"
                                                            data-id="{{ $u->user_id }}"
                                                            data-nama="{{ $u->user_nama }}">
                                                            Pilih
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        {{-- Footer --}}
        @include('kepegawaian.partials.footerkepegawaian')
        </main>
    </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tableAll').DataTable();
        });
    </script>
</body>

</html>
