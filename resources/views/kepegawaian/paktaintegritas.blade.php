<!DOCTYPE html>
<html lang="id">

<head>
    @include('kepegawaian.partials.headkepegawaian')

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            @include('kepegawaian.partials.sidebarkepegawaian')

            {{-- Konten Utama --}}
            <main class="col-md-10 ms-sm-auto p-4">

                {{-- Header --}}
                <div class="navbar-header mb-4 d-flex justify-content-between align-items-center">
                    <h2>Dashboard</h2>
                    <div class="d-flex align-items-center">
                        <input class="form-control me-3" type="text" placeholder="Cari...">
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                                data-bs-toggle="dropdown">
                                <i class="rounded-circle me-2 bi bi-people"></i>
                                Admin
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Profil</a></li>
                                <li><a class="dropdown-item" href="#">Keluar</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Statistik --}}
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5>Total Pegawai</h5>
                                <p class="display-6 text-warning">{{ $dataPegawai->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5>PNS Terkumpul</h5>
                                <p class="display-6 text-success">{{ $jumlahPnsKumpul }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5>PPPK Terkumpul</h5>
                                <p class="display-6 text-primary">{{ $jumlahPppkKumpul }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5>DOWNLOAD DATA</h5>
                                <a href="{{ route('kepegawaian.export', ['id' => $jenis]) }}"
                                    class="btn btn-warning">Export Excel</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabs --}}
                <ul class="nav nav-tabs mb-3" id="dataTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all"
                            type="button" role="tab" aria-controls="all" aria-selected="true">Semua</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pns-tab" data-bs-toggle="tab" data-bs-target="#pns" type="button"
                            role="tab" aria-controls="pns" aria-selected="false">PNS</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pppk-tab" data-bs-toggle="tab" data-bs-target="#pppk" type="button"
                            role="tab" aria-controls="pppk" aria-selected="false">PPPK</button>
                    </li>
                </ul>

                <div class="tab-content" id="dataTabsContent">
                    {{-- Tab Semua --}}
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <table id="tableAll" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Bidang</th>
                                    <th>Status</th>
                                    <th>Status Kumpul</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataPegawai as $no => $user)

                                <tr>
                                    <td>{{ $no + 1 }}</td>
                                    <td>{{ $user->user_nip }}</td>
                                    <td>{{ $user->user_nama }}</td>
                                    <td>{{ $user->jabatan_nama }}</td>
                                    <td>{{ $user->bidang_nama }}</td>
                                    <td>
                                        @if($user->user_jeniskerja == '1')
                                        <span class="badge bg-success">PNS</span>
                                        @elseif($user->user_jeniskerja == '2')
                                        <span class="badge bg-primary">PPPK</span>
                                        @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->kumpulan_status == 1)
                                        <span class="text-success fw-bold">Terkumpul</span>
                                        @else
                                        <span class="text-danger fw-bold">Belum</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->kumpulan_status == 1 && $user->kumpulan_file)
                                        <a href="{{ $user->kumpulan_file }}" target="_blank"
                                            class="btn btn-sm btn-primary">
                                            Lihat File
                                        </a>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Tab PNS --}}
                    <div class="tab-pane fade" id="pns" role="tabpanel" aria-labelledby="pns-tab">
                        <table id="tablePns" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Bidang</th>
                                    <th>Status</th>
                                    <th>Status Kumpul</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataPns as $no => $user)
                                <tr>
                                    <td>{{ $no + 1 }}</td>
                                    <td>{{ $user->user_nip }}</td>
                                    <td>{{ $user->user_nama }}</td>
                                    <td>{{ $user->jabatan_nama }}</td>
                                    <td>{{ $user->bidang_nama }}</td>
                                    <td>
                                        @if($user->user_jeniskerja == '1')
                                        <span class="badge bg-success">PNS</span>
                                        @elseif($user->user_jeniskerja == '2')
                                        <span class="badge bg-primary">PPPK</span>
                                        @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->kumpulan_status == 1)
                                        <span class="text-success fw-bold">Terkumpul</span>
                                        @else
                                        <span class="text-danger fw-bold">Belum</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->kumpulan_status == 1 && $user->kumpulan_file)
                                        <a href="{{ $user->kumpulan_file }}" target="_blank"
                                            class="btn btn-sm btn-primary">
                                            Lihat File
                                        </a>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Tab PPPK --}}
                    <div class="tab-pane fade" id="pppk" role="tabpanel" aria-labelledby="pppk-tab">
                        <table id="tablePppk" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Bidang</th>
                                    <th>Status</th>
                                    <th>Status Kumpul</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataPppk as $no => $user)

                                <tr>
                                    <td>{{ $no + 1 }}</td>
                                    <td>{{ $user->user_nip }}</td>
                                    <td>{{ $user->user_nama }}</td>
                                    <td>{{ $user->jabatan_nama }}</td>
                                    <td>{{ $user->bidang_nama }}</td>
                                    <td>
                                        @if($user->user_jeniskerja == '1')
                                        <span class="badge bg-success">PNS</span>
                                        @elseif($user->user_jeniskerja == '2')
                                        <span class="badge bg-primary">PPPK</span>
                                        @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->kumpulan_status == 1)
                                        <span class="text-success fw-bold">Terkumpul</span>
                                        @else
                                        <span class="text-danger fw-bold">Belum</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->kumpulan_status == 1 && $user->kumpulan_file)
                                        <a href="{{ $user->kumpulan_file }}" target="_blank"
                                            class="btn btn-sm btn-primary">
                                            Lihat File
                                        </a>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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