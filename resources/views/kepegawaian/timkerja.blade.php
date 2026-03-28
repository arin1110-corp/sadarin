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

                <!-- Modal Tambah/Edit -->
                <div class="modal fade" id="modalTim" tabindex="-1" aria-labelledby="modalTimLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form id="formTim" action="{{ route('kepegawaian.tambah.timkerja') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalTimLabel">Tambah Tim Kerja</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Pilih Bidang -->
                                    <div class="mb-3">
                                        <label for="bidang_id" class="form-label">Bidang</label>
                                        <select id="bidang_id" name="timkerja_bidang" class="form-select" required>
                                            <option value="">-- Pilih Bidang --</option>
                                            @foreach ($bidang as $b)
                                                <option value="{{ $b->bidang_id }}">{{ $b->bidang_nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Kepala Bidang -->
                                    <div class="mb-3">
                                        <label for="kepala_bidang" class="form-label">Kepala</label>
                                        <input type="text" id="kepala_bidang" class="form-control" readonly>
                                        <input type="hidden" name="kepala_bidang_id" id="kepala_bidang_id">
                                    </div>

                                    <!-- Nama Tim Kerja -->
                                    <div class="mb-3">
                                        <label for="timkerja_nama" class="form-label">Nama Tim Kerja</label>
                                        <input type="text" name="timkerja_nama" id="timkerja_nama"
                                            class="form-control" required>
                                    </div>

                                    <!-- Ketua Tim -->
                                    <div class="mb-3">
                                        <label for="ketua_tim" class="form-label">Ketua Tim</label>
                                        <select id="ketua_tim" name="timkerja_ketuatim" class="form-select select2"
                                            required>
                                            <option value="">-- Pilih Ketua Tim --</option>
                                            @foreach ($users as $u)
                                                <option value="{{ $u->user_id }}">{{ $u->user_nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Uraian Tim Kerja -->
                                    <div class="mb-3">
                                        <label class="form-label">Uraian Tim Kerja</label>
                                        <ol id="uraian-list">
                                            <li class="mb-2">
                                                <input type="text" name="uraian[]" class="form-control"
                                                    placeholder="Uraian ke-1" required>
                                            </li>
                                        </ol>
                                        <button type="button" class="btn btn-sm btn-secondary mt-2"
                                            id="tambah-uraian">
                                            <i class="bi bi-plus-lg"></i> Tambah Uraian
                                        </button>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success" id="submitBtn">Simpan</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="card-body">
                    {{-- Tab Semua --}}
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <table id="tableAll" class="table table-striped table-bordered w-100 align-middle">
                            <thead class="table-dark">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Nama Bidang</th>
                                    <th>Nama Kepala</th>
                                    <th>Nama Tim Kerja</th>
                                    <th>Ketua Tim Kerja</th>
                                    <th>Anggota</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($timkerja as $index => $tim)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $tim->bidang_nama }}</td>
                                        <td>{{ $tim->kepala_bidang ?? '-' }}</td>
                                        <td>{{ $tim->timkerja_nama }}</td>
                                        <td>{{ $tim->ketua_tim ?? '-' }}</td>
                                        <td><a href="{{ route('kepegawaian.anggota.timkerja', $tim->timkerja_id) }}"><button class="btn btn-info btn-sm">Anggota</button></a></td>
                                        <td>
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modalTim" data-tim-id="{{ $tim->timkerja_id }}"
                                                data-bidang-id="{{ $tim->bidang_id }}"
                                                data-tim-nama="{{ $tim->timkerja_nama }}"
                                                data-ketua-id="{{ $tim->ketua_tim_id }}"
                                                data-uraian="{{ implode('|||', is_array($tim->timkerja_uraian) ? $tim->timkerja_uraian : explode('|||', $tim->timkerja_uraian)) }}">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
    <script>
        $(document).ready(function() {

            // select2
            $('#ketua_tim').select2({
                dropdownParent: $('#modalTim'),
                width: '100%',
                placeholder: "-- Pilih Ketua Tim --"
            });

            $('#modalTim').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let timId = button.data('tim-id');

                // reset form
                $('#formTim')[0].reset();
                $('#uraian-list').html(`
            <li class="mb-2 d-flex gap-2">
                <input type="text" name="uraian[]" class="form-control" placeholder="Uraian ke-1" required>
            </li>
        `);

                // default (mode tambah)
                $('#modalTimLabel').text('Tambah Tim Kerja');
                $('#submitBtn').text('Simpan');
                $('#formTim').attr('action', '{{ route('kepegawaian.tambah.timkerja') }}');

                $('#ketua_tim').val(null).trigger('change');

                // ================= EDIT MODE =================
                if (timId) {
                    $('#modalTimLabel').text('Edit Tim Kerja');
                    $('#submitBtn').text('Update');
                    $('#formTim').attr('action', '/kepegawaian/timkerja/update/' + timId);

                    let bidangId = button.data('bidang-id');
                    let timNama = button.data('tim-nama');
                    let ketuaId = button.data('ketua-id');
                    let uraian = button.data('uraian').split('|||');

                    $('#bidang_id').val(bidangId);
                    $('#timkerja_nama').val(timNama);
                    $('#ketua_tim').val(ketuaId).trigger('change');

                    // ambil kepala bidang
                    $.get('/get-kepala-bidang/' + bidangId, function(data) {
                        $('#kepala_bidang').val(data.user_nama);
                        $('#kepala_bidang_id').val(data.user_id);
                    });

                    // isi uraian
                    $('#uraian-list').empty();
                    uraian.forEach((u, i) => {
                        $('#uraian-list').append(`
                    <li class="mb-2 d-flex gap-2">
                        <input type="text" name="uraian[]" class="form-control" value="${u}" required>
                        <button type="button" class="btn btn-danger btn-sm btn-hapus-uraian">
                            <i class="bi bi-trash"></i>
                        </button>
                    </li>
                `);
                    });
                }
            });

            // tambah uraian
            $('#tambah-uraian').on('click', function() {
                let index = $('#uraian-list li').length + 1;

                $('#uraian-list').append(`
            <li class="mb-2 d-flex gap-2">
                <input type="text" name="uraian[]" class="form-control" placeholder="Uraian ke-${index}" required>
                <button type="button" class="btn btn-danger btn-sm btn-hapus-uraian">
                    <i class="bi bi-trash"></i>
                </button>
            </li>
        `);
            });

            // hapus uraian (delegasi event biar aman)
            $(document).on('click', '.btn-hapus-uraian', function() {
                $(this).closest('li').remove();
            });

            // change bidang → auto kepala
            $('#bidang_id').on('change', function() {
                let bidangId = $(this).val();

                if (bidangId) {
                    $.get('/get-kepala-bidang/' + bidangId, function(data) {
                        $('#kepala_bidang').val(data.user_nama);
                        $('#kepala_bidang_id').val(data.user_id);
                    });
                } else {
                    $('#kepala_bidang').val('');
                    $('#kepala_bidang_id').val('');
                }
            });

        });
    </script>
</body>

</html>
