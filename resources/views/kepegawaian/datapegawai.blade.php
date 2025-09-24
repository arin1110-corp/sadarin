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

                {{-- Statistik --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-people-fill text-warning fs-2 mb-2"></i>
                                <h6 class="fw-semibold">Total Pegawai</h6>
                                <p class="display-6 fw-bold text-warning">{{ @$totalPegawai }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-person-badge text-success fs-2 mb-2"></i>
                                <h6 class="fw-semibold">PNS</h6>
                                <p class="display-6 fw-bold text-success">{{ @$datapnspegawai }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-person-workspace text-primary fs-2 mb-2"></i>
                                <h6 class="fw-semibold">PPPK</h6>
                                <p class="display-6 fw-bold text-primary">{{ @$datapppkpegawai }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabs Data Pegawai --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pb-0">
                        <ul class="nav nav-tabs card-header-tabs" id="dataTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all"
                                    type="button" role="tab">Semua</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pns-tab" data-bs-toggle="tab" data-bs-target="#pns"
                                    type="button" role="tab">PNS</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pppk-tab" data-bs-toggle="tab" data-bs-target="#pppk"
                                    type="button" role="tab">PPPK</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="dataTabsContent">
                            {{-- Tab Semua --}}
                            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                                <table id="tableAll" class="table table-striped table-bordered w-100 align-middle">
                                    <thead class="table-dark">
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataPegawai as $no => $user)
                                        <tr>
                                            <td class="text-center">{{ $no + 1 }}</td>
                                            <td>{{ $user->user_nip }}</td>
                                            <td>{{ $user->user_nama }}</td>
                                            <td class="text-center">
                                                @if($user->user_jeniskerja == '1')
                                                <span class="badge bg-success">PNS</span>
                                                @elseif ($user->user_jeniskerja == '2')
                                                <span class="badge bg-primary">PPPK</span>
                                                @else
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#modalDetailAll{{ $user->user_id }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Tab PNS --}}
                            <div class="tab-pane fade" id="pns" role="tabpanel" aria-labelledby="pns-tab">
                                <table id="tablePns" class="table table-striped table-bordered w-100 align-middle">
                                    <thead class="table-dark">
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataPegawai as $no => $user)
                                        @if($user->user_jeniskerja == '1') {{-- 1 = PNS --}}
                                        <tr>
                                            <td class="text-center">{{ $no + 1 }}</td>
                                            <td>{{ $user->user_nip }}</td>
                                            <td>{{ $user->user_nama }}</td>
                                            <td class="text-center">
                                                @if($user->user_status == '1')
                                                <span class="badge bg-success">Aktif</span>
                                                @else
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#modalDetailAll{{ $user->user_id }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Tab PPPK --}}
                            <div class="tab-pane fade" id="pppk" role="tabpanel" aria-labelledby="pppk-tab">
                                <table id="tablePppk" class="table table-striped table-bordered w-100 align-middle">
                                    <thead class="table-dark">
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataPegawai as $no => $user)
                                        @if($user->user_jeniskerja == '2') {{-- 2 = PPPK --}}
                                        <tr>
                                            <td class="text-center">{{ $no + 1 }}</td>
                                            <td>{{ $user->user_nip }}</td>
                                            <td>{{ $user->user_nama }}</td>
                                            <td class="text-center">
                                                @if($user->user_status == '1')
                                                <span class="badge bg-success">Aktif</span>
                                                @else
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#modalDetailAll{{ $user->user_id }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Modals: dipisah di luar tabel agar HTML valid --}}
                {{-- Modals untuk "All" --}}
                @foreach($dataPegawai as $user)
                <div class="modal fade" id="modalDetailAll{{ $user->user_id }}" tabindex="-1"
                    aria-labelledby="modalDetailAllLabel{{ $user->user_id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-dark text-white">
                                <h5 class="modal-title" id="modalDetailAllLabel{{ $user->user_id }}">Detail Pegawai</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-4 text-center">
                                        <img src="{{ ($user->user_foto && $user->user_foto != '-') ? asset($user->user_foto) : asset('assets/image/pemprov.png') }}"
                                            alt="Foto Pegawai" class="img-thumbnail rounded shadow-sm" width="384px"
                                            height="auto">
                                    </div>
                                    <div class="col-md-8">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="30%" colspan="2" class="text-center">*** IDENTITAS PEGAWAI
                                                    ***</th>
                                            </tr>
                                            <tr>
                                                <th width="30%">NIP</th>
                                                <td>: {{ $user->user_nip }}</td>
                                            </tr>
                                            <tr>
                                                <th>NIK</th>
                                                <td>: {{ $user->user_nik }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nama</th>
                                                <td>: {{ $user->user_nama }}</td>
                                            </tr>
                                            <tr>
                                                <th>Gelar Depan</th>
                                                <td>: {{ $user->user_gelardepan ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Gelar Belakang</th>
                                                <td>: {{ $user->user_gelarbelakang ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jenis Kelamin</th>
                                                <td>:
                                                    {{ $user->user_jk == 'L' ? 'Laki-laki' : ($user->user_jk == 'P' ? 'Perempuan' : '-') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Lahir</th>
                                                <td>:
                                                    {{ \Carbon\Carbon::parse($user->user_tgllahir)->translatedFormat('j F Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Pendidikan</th>
                                                <td>:
                                                    {{ $user->pendidikan_jenjang ?? ($user->pendidikan->pendidikan_jurusan ?? '-') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="2" class="text-center">*** IDENTITAS JABATAN ***</th>
                                            </tr>
                                            <tr>
                                                <th>Jabatan</th>
                                                <td>: {{ $user->jabatan_nama ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Golongan</th>
                                                <td>: {{ $user->golongan_nama.' - '.$user->golongan_pangkat?? '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Eselon</th>
                                                <td>: {{ $user->eselon_nama ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kelas Jabatan</th>
                                                <td>: Kelas Jabatan {{ $user->user_kelasjabatan ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Unit Kerja</th>
                                                <td>: {{ $user->bidang_nama ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>TMT</th>
                                                <td>:
                                                    {{ \Carbon\Carbon::parse($user->user_tmt)->translatedFormat('j F Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>SPMT</th>
                                                <td>:
                                                    {{ \Carbon\Carbon::parse($user->user_spmt)->translatedFormat('j F Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Jenis Kerja</th>
                                                <td>:
                                                    {{ $user->user_jeniskerja == '1' ? 'PNS' : ($user->user_jeniskerja == '2' ? 'PPPK' : '-') }}
                                                </td>
                                            </tr>
                                            <!-- Informasi Kontak -->
                                            <tr>
                                                <th colspan="2" class="text-center">*** INFORMASI KONTAK ***</th>
                                            </tr>
                                            <tr>
                                                <th>Alamat</th>
                                                <td>: {{ $user->user_alamat ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>No. Telepon</th>
                                                <td>: {{ $user->user_notelp ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>: {{ $user->user_email ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>BPJS</th>
                                                <td>: {{ $user->user_bpjs ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>No. Rekening</th>
                                                <td>: {{ $user->user_norek ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>NPWP</th>
                                                <td>: {{ $user->user_npwp ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jumlah Tanggungan</th>
                                                <td>: {{ $user->user_jmltanggungan ?? '-' }} Orang</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>:
                                                    @if($user->user_status == '1')
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
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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