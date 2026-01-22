<!DOCTYPE html>
<html lang="id">

<head>
    @include('kepegawaian.partials.headkepegawaian')

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" />
    <style>
        table.dataTable {
            table-layout: fixed;
            width: 100% !important;
        }

        .dataTables_wrapper .dataTables_processing {
            background: rgba(255, 255, 255, 0.8);
        }
    </style>

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
                        <input class="form-control form-control-sm" type="text" placeholder="🔍 Cari data..." />
                        <div class="dropdown">
                            <a href="#"
                                class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-4 me-2 text-primary"></i>
                                <span class="fw-semibold">Admin</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>
                                        Profil</a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"><i
                                            class="bi bi-box-arrow-right me-2"></i>
                                        Keluar</a>
                                </li>
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
                                <p class="display-6 fw-bold text-warning">
                                    {{ @$totalPegawai }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-person-check-fill text-muted fs-2 mb-2"></i>
                                <h6 class="fw-semibold">Total Pegawai Aktif</h6>
                                <p class="display-6 fw-bold text-muted">
                                    {{ @$dataPegawaiaktif }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-person-x-fill text-secondary fs-2 mb-2"></i>
                                <h6 class="fw-semibold">Total Pegawai Non Aktif</h6>
                                <p class="display-6 fw-bold text-secondary">
                                    {{ @$dataPegawainonaktif }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-person-badge text-success fs-2 mb-2"></i>
                                <h6 class="fw-semibold">PNS</h6>
                                <p class="display-6 fw-bold text-success">
                                    {{ @$datapnspegawai }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-person-workspace text-primary fs-2 mb-2"></i>
                                <h6 class="fw-semibold">PPPK</h6>
                                <p class="display-6 fw-bold text-primary">
                                    {{ @$datapppkpegawai }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-mortarboard text-info fs-2 mb-2"></i>
                                <h6 class="fw-semibold">PPPK Paruh Waktu</h6>
                                <p class="display-6 fw-bold text-info">
                                    {{ @$datapppkparuhwaktu }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <i class="bi bi-person-lines-fill text-secondary fs-2 mb-2"></i>
                                <h6 class="fw-semibold">PJLP</h6>
                                <p class="display-6 fw-bold text-secondary">
                                    {{ @$datanonasn }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tombol trigger -->
                <form method="POST" action="{{ route('kepegawaian.export.data.excel.pegawai') }}">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        Export Pemuktahiran
                    </button>
                </form>
                <br />
                <button class="btn btn-sm btn-success btn-modal" data-action="export_rekap_data">
                    <i class="bi bi-file-earmark-excel"></i> Export Data
                </button>
                <!-- <button type="button" class="btn btn-success me-2" data-bs-toggle="modal"
                    data-bs-target="#modalTambahPegawai">
                    Tambah Data Pegawai
                </button> -->
                <br />
                <br />

                {{-- Tabs Data Pegawai --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pb-0">
                        <ul class="nav nav-tabs card-header-tabs" id="dataTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="all-tab" data-bs-toggle="tab"
                                    data-bs-target="#all" type="button" role="tab">
                                    Semua
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pns-tab" data-bs-toggle="tab" data-bs-target="#pns"
                                    type="button" role="tab">
                                    PNS
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pppk-tab" data-bs-toggle="tab" data-bs-target="#pppk"
                                    type="button" role="tab">
                                    PPPK
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pppkparuhwaktu-tab" data-bs-toggle="tab"
                                    data-bs-target="#pppkparuhwaktu" type="button" role="tab">
                                    PPPK Paruh Waktu
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="nonasn-tab" data-bs-toggle="tab"
                                    data-bs-target="#nonasn" type="button" role="tab">
                                    PJLP
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="dataTabsContent">
                            {{-- Tab Semua --}}
                            <div class="tab-pane fade show active" id="all" role="tabpanel"
                                aria-labelledby="all-tab">
                                <table id="tableAll" class="table table-striped table-bordered w-100 align-middle">
                                    <thead class="table-dark">
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Jenis Kerja</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataPegawai as $no => $user)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $no + 1 }}
                                                </td>
                                                <td>{{ $user->user_nip }}</td>
                                                <td>{{ $user->user_nama }}</td>
                                                <td class="text-center">
                                                    @if ($user->user_jeniskerja == '1')
                                                        <span class="badge bg-success">PNS</span>
                                                    @elseif($user->user_jeniskerja == '2')
                                                        <span class="badge bg-primary">PPPK</span>
                                                    @elseif($user->user_jeniskerja == '3')
                                                        <span class="badge bg-danger">PPPK Paruh Waktu</span>
                                                    @elseif($user->user_jeniskerja == '4')
                                                        <span class="badge bg-secondary">PJLP</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($user->user_status == '1')
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-info btn-modal"
                                                        data-id="{{ $user->user_id }}" data-action="detail">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-warning btn-modal"
                                                        data-id="{{ $user->user_id }}" data-action="ganti_status">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-modal"
                                                        data-id="{{ $user->user_id }}"
                                                        data-action="ganti_jenis_kerja">
                                                        <i class="bi bi-people-fill"></i>
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
                                        @foreach ($listpegawaiPNS as $no => $user)
                                            {{-- 1 = PNS --}}
                                            <tr>
                                                <td class="text-center">
                                                    {{ $no + 1 }}
                                                </td>
                                                <td>{{ $user->user_nip }}</td>
                                                <td>{{ $user->user_nama }}</td>
                                                <td class="text-center">
                                                    @if ($user->user_status == '1')
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-info btn-modal"
                                                        data-id="{{ $user->user_id }}" data-action="detail">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-warning btn-modal"
                                                        data-id="{{ $user->user_id }}" data-action="ganti_status">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-modal"
                                                        data-id="{{ $user->user_id }}"
                                                        data-action="ganti_jenis_kerja">
                                                        <i class="bi bi-people-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
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
                                        @foreach ($listpegawaiPPPK as $no => $user)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $no + 1 }}
                                                </td>
                                                <td>{{ $user->user_nip }}</td>
                                                <td>{{ $user->user_nama }}</td>
                                                <td class="text-center">
                                                    @if ($user->user_status == '1')
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-info btn-modal"
                                                        data-id="{{ $user->user_id }}" data-action="detail">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-warning btn-modal"
                                                        data-id="{{ $user->user_id }}" data-action="ganti_status">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-modal"
                                                        data-id="{{ $user->user_id }}"
                                                        data-action="ganti_jenis_kerja">
                                                        <i class="bi bi-people-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Tab PPPK Paruh Waktu --}}
                            <div class="tab-pane fade" id="pppkparuhwaktu" role="tabpanel"
                                aria-labelledby="pppk-tab">
                                <table id="tablePppkParuhWaktu"
                                    class="table table-striped table-bordered w-100 align-middle">
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
                                        @foreach ($listpegawaiPPPKParuhWaktu as $no => $user)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $no + 1 }}
                                                </td>
                                                <td>{{ $user->user_nip }}</td>
                                                <td>{{ $user->user_nama }}</td>
                                                <td class="text-center">
                                                    @if ($user->user_status == '1')
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-info btn-modal"
                                                        data-id="{{ $user->user_id }}" data-action="detail">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-warning btn-modal"
                                                        data-id="{{ $user->user_id }}" data-action="ganti_status">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-modal"
                                                        data-id="{{ $user->user_id }}"
                                                        data-action="ganti_jenis_kerja">
                                                        <i class="bi bi-people-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Tab PJLP --}}
                            <div class="tab-pane fade" id="nonasn" role="tabpanel" aria-labelledby="nonasn-tab">
                                <table id="tableNonAsn" class="table table-striped table-bordered w-100 align-middle">
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
                                        @foreach ($listpegawaiNonASN as $no => $user)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $no + 1 }}
                                                </td>
                                                <td>{{ $user->user_nip }}</td>
                                                <td>{{ $user->user_nama }}</td>
                                                <td class="text-center">
                                                    @if ($user->user_status == '1')
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-info btn-modal"
                                                        data-id="{{ $user->user_id }}" data-action="detail">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-warning btn-modal"
                                                        data-id="{{ $user->user_id }}" data-action="ganti_status">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-modal"
                                                        data-id="{{ $user->user_id }}"
                                                        data-action="ganti_jenis_kerja">
                                                        <i class="bi bi-people-fill"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="modalDetail" tabindex="-1">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail Pegawai</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body" id="modalContent">
                                <div class="text-muted text-center p-4">
                                    Memuat data...
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

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <!-- {{-- Inisialisasi DataTables --}}
    <script>
        $(document).ready(function() {
            $("#tableAll").DataTable();
            $("#tablePns").DataTable();
            $("#tablePppk").DataTable();
            $("#tablepjlp").DataTable();
            $("#tablePppkParuhWaktu").DataTable();
            $("#tableAll").DataTable({
                deferRender: true,
                pageLength: 10,
                processing: true,
                language: {
                    processing: "Memuat data..."
                }
            });
            $("#tablePns").DataTable({
                deferRender: true,
                pageLength: 10,
                processing: true,
                language: {
                    processing: "Memuat data..."
                }
            });
            $("#tablePppk").DataTable({
                deferRender: true,
                pageLength: 10,
                processing: true,
                language: {
                    processing: "Memuat data..."
                }
            });
            $("#tableNonAsn").DataTable({
                deferRender: true,
                pageLength: 10,
                processing: true,
                language: {
                    processing: "Memuat data..."
                }
            });
            $("#tablePppkParuhWaktu").DataTable({
                deferRender: true,
                pageLength: 10,
                processing: true,
                language: {
                    processing: "Memuat data..."
                }
            });
        });
    </script> -->
    <script>
        $(document).ready(function() {

            // ==================================
            // KONFIGURASI GLOBAL DATATABLE
            // ==================================
            const DT_CONFIG = {
                deferRender: true,
                pageLength: 10,
                lengthChange: false,
                processing: false,
                autoWidth: false,
                ordering: true,
                info: false,
                stateSave: true,
                searchDelay: 400,
                language: {
                    search: "Cari:"
                },
                columnDefs: [{
                    orderable: false,
                    searchable: false,
                    targets: -1
                }]
            };

            // ==================================
            // SIMPAN INSTANCE DATATABLE
            // ==================================
            const tables = new Map();

            function initTable($table) {
                if (!$table.length) return;

                if (!$.fn.DataTable.isDataTable($table)) {
                    const dt = $table.DataTable(DT_CONFIG);
                    tables.set($table[0], dt);
                }
            }

            // ==================================
            // INIT TAB AKTIF SAAT LOAD
            // ==================================
            initTable($('.tab-pane.active table'));

            // ==================================
            // LAZY INIT TAB SAAT DIBUKA
            // ==================================
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {

                const targetPane = $($(e.target).data('bs-target'));
                const table = targetPane.find('table');

                initTable(table);

                // adjust hanya table aktif
                setTimeout(() => {
                    if ($.fn.DataTable.isDataTable(table)) {
                        table.DataTable().columns.adjust();
                    }
                }, 80);
            });


            // ==================================
            // TOOLTIP (DITUNDA AGAR TIDAK BLOCK)
            // ==================================
            setTimeout(() => {
                $('[data-bs-toggle="tooltip"]').tooltip();
            }, 800);

        });
    </script>
    <script>
        $(document).on('click', '.btn-modal', function() {
            let id = $(this).data('id');
            let action = $(this).data('action');

            $('#modalContent').html('Loading...');
            $('#modalDetail').modal('show');

            $.get(`/kepegawaian/data/pegawai/${id}/${action}`, function(html) {
                $('#modalContent').html(html);
            });
        });
    </script>
    <script>
        $('#exportType').on('change', function() {
            $('#summaryOptions, #detailOptions').addClass('d-none');

            if (this.value === 'summary') {
                $('#summaryOptions').removeClass('d-none');
            }
            if (this.value === 'detail') {
                $('#detailOptions').removeClass('d-none');
            }
        });
    </script>

</body>

</html>
