<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SADARIN - Sistem Data dan Arsip Internal</title>
    <link rel="icon" href="{{ asset('assets/image/pemprov.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 1400px;
            margin-top: 40px;
            background: white;
            box-shadow: 0 15px 15px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            padding: 40px;
        }

        /* ================= TITLE ================= */
        .title h1 {
            font-weight: bold;
            font-size: clamp(28px, 5vw, 50px);
        }

        .title .in {
            color: orangered;
        }

        /* ================= ORG ================= */
        .org-chart {
            margin-top: 60px;
        }

        .org-node {
            width: 100%;
            max-width: 1100px;
            margin: 20px auto;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
        }

        .org-node img {
            border-radius: 8px;
            border: 2px solid #666;
        }

        .org-node h6,
        .org-node small {
            margin: 2px 0;
            word-wrap: break-word;
        }

        /* ================= CARD ================= */
        .card {
            transition: 0.2s;
            border-radius: 12px;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .card-footer {
            background: transparent;
            border-top: none;
        }

        .card-footer .btn {
            width: 100%;
        }

        /* ================= MODAL FIX ================= */
        .modal-dialog {
            margin: auto !important;
            /* 🔥 fix biar center */
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        .modal-content {
            width: 100%;
            border-radius: 12px;
        }

        /* TABLE */
        table {
            width: 100% !important;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {

            .container {
                padding: 20px;
                margin-top: 10px;
            }

            .title div {
                font-size: 16px !important;
            }

            .org-node {
                padding: 15px;
            }

            .org-node img {
                width: 80px !important;
                height: 100px !important;
            }

            .card img {
                width: 100% !important;
                height: 140px !important;
            }

            h5 {
                font-size: 18px;
            }

            h6 {
                font-size: 14px;
            }

            .card-body {
                padding: 8px;
            }

            .btn {
                font-size: 12px;
                padding: 4px 8px;
            }

            table {
                font-size: 12px;
            }

            /* 🔥 modal full HP tetap aman */
            .modal-dialog {
                max-width: 100% !important;
                margin: 0 !important;
                height: 100%;
            }

            .modal-content {
                height: 100%;
                border-radius: 0;
            }
        }

        footer {
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container text-center">
        <!-- Header -->
        <div class="title">
            <h5 class="mb-4">
                <img src="{{ asset('assets/image/pemprov.png') }}" width="150">
            </h5>
            <h1>SADAR<span class="in">IN</span></h1>
            <div style="font-size: 22px; color: #666;">
                Sistem Aplikasi Data dan Arsip Internal Dinas Kebudayaan Provinsi Bali
            </div>
            <div style="font-size: 30px; color: #666;">
                <strong>PENGELOLAAN TIM KERJA</strong>
            </div>
            <div style="font-size: 25px; color: #999;">
                <strong>{{ $timkerja->timkerja_nama }}</strong>
            </div>

            <!-- Kartu Tim Kerja -->
            <div class="org-chart">
                <div class="org-node p-4 shadow-sm">
                    <!-- Nama Bidang -->
                    <h5 style="color: #020202; font-weight: 600;">Bidang: {{ $timkerja_data->bidang_nama ?? '-' }}</h5>

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

                        @if ($anggotaGrouped->isNotEmpty())

                            @foreach ($anggotaGrouped as $lokasi => $listAnggota)
                                <div class="mb-4">
                                    <h5 class="text-primary">
                                        <i class="bi bi-geo-alt"></i>
                                        {{ $lokasi ?? 'Tanpa Lokasi Kerja' }}
                                    </h5>

                                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 mt-2">
                                        @foreach ($listAnggota as $a)
                                            <div class="col">
                                                <div class="card text-center h-100 border-0 shadow-sm">

                                                    @if (!empty($a->user_foto))
                                                        <center>
                                                            <img src="{{ $a->user_foto != '-' ? asset($a->user_foto) : asset('assets/image/pemprov.png') }}"
                                                                class="img-fluid rounded"
                                                                style="width:100%; height:160px; object-fit:cover;">
                                                        </center>
                                                    @else
                                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                                                            style="height: 200px;">
                                                            No Image
                                                        </div>
                                                    @endif

                                                    <div class="card-body p-2">
                                                        <h6 class="mb-0">{{ $a->user_nama }}</h6>
                                                        <small class="text-muted">{{ $a->jabatan_nama ?? '-' }}</small>

                                                    </div>
                                                    <div class="card-footer p-2">
                                                        <button class="btn btn-danger btn-sm mt-2 btnHapusAnggota"
                                                            data-id="{{ $a->user_id }}"
                                                            data-nama="{{ $a->user_nama }}">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
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


            <div class="modal fade" id="modalTambahAnggota" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-md-down">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Anggota Tim</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body p-2">

                            <div class="table-responsive">
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
                                </table>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <!-- Modal Uraian -->
            <div class="modal fade" id="modalUraian" tabindex="-1" aria-labelledby="modalUraianLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('timkerja.update_uraian', $timkerja->timkerja_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalUraianLabel">Ubah Uraian Tim</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @php
                                    // Pisahkan uraian berdasarkan pembatas unik |||
                                    $uraianList = explode('|||', $timkerja_data->timkerja_uraian);
                                @endphp

                                <ol id="uraian-list-edit">
                                    @forelse ($uraianList as $index => $item)
                                        <li class="mb-2">
                                            <input type="text" name="uraian[]" class="form-control"
                                                value="{{ trim($item) }}"
                                                placeholder="Uraian ke-{{ $index + 1 }}">
                                        </li>
                                    @empty
                                        <li class="mb-2">
                                            <input type="text" name="uraian[]" class="form-control"
                                                placeholder="Uraian ke-1">
                                        </li>
                                    @endforelse
                                </ol>

                                <button type="button" class="btn btn-sm btn-secondary mt-2" id="tambah-uraian">
                                    <i class="bi bi-plus-lg"></i> Tambah Uraian
                                </button>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Uraian</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="text-center mt-5 py-4">
                <div>
                    &copy; {{ date('Y') }} <strong>Dinas Kebudayaan Provinsi Bali</strong> —
                    <span class="fw-bold">SADARIN</span>
                </div>
                <div class="mt-1">
                    <i class="bi bi-code-slash"></i> Dibuat oleh
                    <span class="text-danger mx-1"><i class="bi bi-heart-fill"></i></span>
                    <span class="text-dark">Pranata Komputer Ahli Pertama</span>
                </div>
            </footer>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
<script>
    document.getElementById('tambah-uraian').addEventListener('click', function() {
        const list = document.getElementById('uraian-list-edit');
        const index = list.children.length + 1;
        const li = document.createElement('li');
        li.className = 'mb-2';
        li.innerHTML =
            `<input type="text" name="uraian[]" class="form-control" placeholder="Uraian ke-${index}">`;
        list.appendChild(li);
    });
</script>
<script>
    let table;

    $('#modalTambahAnggota').on('shown.bs.modal', function() {

        if ($.fn.DataTable.isDataTable('#tablePegawaiModal')) {
            return; // biar tidak double init
        }

        table = $('#tablePegawaiModal').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            scrollX: true,
            autoWidth: false,
            ajax: "{{ route('timkerja.users.ajax', $timkerja->timkerja_id) }}",
            columnDefs: [{
                targets: [2, 3], // jabatan & bidang
                className: 'd-none d-md-table-cell'
            }],
            columns: [{
                    data: 'user_foto',
                    render: function(data) {
                        if (data && data !== '-') {
                            return `<img src="/${data}" width="50">`;
                        }
                        return `<img src="/assets/image/pemprov.png" width="50">`;
                    }
                },
                {
                    data: 'user_nama'
                },
                {
                    data: 'jabatan_nama'
                },
                {
                    data: 'bidang_nama'
                },
                {
                    data: 'tim_nama',
                    render: function(data) {
                        if (!data) {
                            return `<span class="text-muted">Belum ada tim kerja</span>`;
                        }
                        return data;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {

                        if (row.status === 'ketua') {
                            return `<button class="btn btn-dark btn-sm" disabled>
                        Ketua Tim
                    </button>`;
                        }

                        if (row.status === 'tim_ini') {
                            return `<button class="btn btn-secondary btn-sm" disabled>
                        Sudah di Tim Ini
                    </button>`;
                        }

                        if (row.status === 'tim_lain') {
                            return `<button class="btn btn-warning btn-sm" disabled>
                        Sudah di Tim Lain
                    </button>`;
                        }

                        return `<button class="btn btn-primary btn-sm pilihPegawaiBtn"
                    data-id="${row.user_id}"
                    data-nama="${row.user_nama}">
                    Pilih
                </button>`;
                    }
                },
            ]
        });

    });

    $('#tablePegawaiModal').on('click', '.pilihPegawaiBtn', function(e) {
        e.preventDefault();

        let btn = $(this);
        let userId = btn.data('id');
        let userName = btn.data('nama');

        Swal.fire({
            title: 'Konfirmasi',
            text: "Tambahkan " + userName + "?",
            icon: 'question',
            showCancelButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ route('timkerja.tambah_anggota', $timkerja->timkerja_id) }}", {
                    _token: "{{ csrf_token() }}",
                    pegawai_id: [userId]
                }, function() {
                    Swal.fire('Berhasil', '', 'success').then(() => {
                        location.reload();
                    });
                });
            }
        });
    });

    $('.btnHapusAnggota').on('click', function() {

        let btn = $(this);
        let userId = btn.data('id');
        let userName = btn.data('nama');

        Swal.fire({
            title: 'Hapus Anggota?',
            text: userName + ' akan dikeluarkan dari tim',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: `/timkerja/{{ $timkerja->timkerja_id }}/anggota/${userId}`,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {

                        Swal.fire('Berhasil', 'Anggota dihapus', 'success')
                            .then(() => {
                                location.reload(); // 🔥 simple & aman
                            });

                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menghapus', 'error');
                    }
                });

            }
        });
    });
</script>


</html>
