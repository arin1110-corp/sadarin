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
            max-width: 1600px;
            margin-top: 40px;
            background: white;
            box-shadow: 0 15px 15px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            padding: 40px;
        }

        .title h1 {
            font-weight: bold;
            font-size: 50px;
            line-height: 1.2;
        }

        .title .in {
            color: orangered;
        }

        /* Struktur Organisasi */
        .org-chart {
            margin-top: 60px;
            position: relative;
        }

        .org-node {
            width: 1100px;
            min-height: 400px;
            margin: 20px auto;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
        }

        .org-node ol {
            padding-left: 20px;
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

        /* Garis vertikal dari induk ke anak */
        .org-connector-vertical {
            width: 2px;
            height: 40px;
            background: #ccc;
            margin: 0 auto;
        }

        .org-children {
            display: flex;
            justify-content: center;
            gap: 60px;
            flex-wrap: nowrap;
            margin-top: 20px;
            position: relative;
        }

        /* Garis horizontal penghubung antar anak */
        .org-children::before {
            content: "";
            position: absolute;
            top: -20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #ccc;
        }

        /* Garis kecil dari kotak ke garis horizontal */
        .org-children .org-node::before {
            content: "";
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 20px;
            background: #ccc;
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
                                                <h6 class="card-title mb-0">{{ $a->user_nama ?? 'Tidak ada nama' }}</h6>
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
                                                        data-id="{{ $u->user_id }}" data-nama="{{ $u->user_nama }}">
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
    var table = $('#tablePegawaiModal').DataTable(); // simpan instance DataTable

    $('#tablePegawaiModal').on('click', '.pilihPegawaiBtn', function(e) {
        e.preventDefault();
        var btn = $(this);
        var userId = btn.data('id');
        var userName = btn.data('nama');

        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah yakin ingin menambahkan " + userName + " ke tim?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, tambahkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('timkerja.tambah_anggota', $timkerja->timkerja_id) }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        pegawai_id: [userId]
                    },
                    success: function(response) {
                        Swal.fire('Berhasil!', userName + ' telah ditambahkan ke tim.',
                            'success');

                        // Disable tombol "Pilih" setelah ditambahkan
                        btn.prop('disabled', true).text('Sudah di Tim Ini');

                        // Jika mau update kolom "Tim Kerja" di DataTable
                        table.cell(btn.closest('tr').find('td:eq(4)')).data(
                            '{{ $timkerja->timkerja_nama }}').draw(false);

                        // Opsional: update daftar anggota di halaman utama dengan AJAX atau reload
                        // location.reload(); <-- jika ingin full reload
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Gagal menambahkan anggota.', 'error');
                    }
                });
            }
        });
    });
</script>
<script>
    $('#tablePegawaiModal').on('click', '.pilihPegawaiBtn', function(e) {
        e.preventDefault();
        var btn = $(this); // tombol yang diklik
        var row = btn.closest('tr'); // baris tabel
        var userId = btn.data('id');
        var userName = btn.data('nama');

        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah yakin ingin menambahkan " + userName + " ke tim?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, tambahkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('timkerja.tambah_anggota', $timkerja->timkerja_id) }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        pegawai_id: [userId]
                    },
                    success: function(response) {
                        Swal.fire('Berhasil!', userName + ' telah ditambahkan ke tim.',
                                'success')
                            .then(() => {
                                // Tutup modal
                                $('#modalTambahAnggota').modal('hide');
                                // Refresh halaman supaya semua update muncul
                                location.reload();
                            });
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Gagal menambahkan anggota.', 'error');
                    }
                });
            }
        });
    });
</script>

</html>
