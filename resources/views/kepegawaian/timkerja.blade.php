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
                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahTim">
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

                <!-- Modal -->
                <div class="modal fade" id="modalTambahTim" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form action="{{ route('kepegawaian.tambah.timkerja') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah Tim Kerja</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

                                    <!-- Kepala Bidang (otomatis dari JS) -->
                                    <div class="mb-3">
                                        <label for="kepala_bidang" class="form-label">Kepala Bidang</label>
                                        <input type="text" id="kepala_bidang" class="form-control" readonly>
                                        <input type="hidden" name="kepala_bidang_id" id="kepala_bidang_id">
                                    </div>

                                    <!-- Nama Tim Kerja -->
                                    <div class="mb-3">
                                        <label for="timkerja_nama" class="form-label">Nama Tim Kerja</label>
                                        <input type="text" name="timkerja_nama" id="timkerja_nama"
                                            class="form-control" required>
                                    </div>

                                    <!-- Ketua Tim (select searchable) -->
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
                                        <label for="uraian_tim" class="form-label">Uraian Tim Kerja</label>
                                        <textarea id="timkerja_uraian" class="form-control" rows="5" name="timkerja_uraian"></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Simpan</button>
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
                                    <th>Nama Kepala Bidang</th>
                                    <th>Nama Tim Kerja</th>
                                    <th>Ketua Tim Kerja</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($timkerja as $index => $tim)
                                    <tr class="text-center">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $tim->bidang_nama }}</td>
                                        <td>{{ $tim->kepala_bidang ?? '-' }}</td>
                                        <td>{{ $tim->timkerja_nama }}</td>
                                        <td>{{ $tim->ketua_tim ?? '-' }}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm">
                                                Detail
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
            $('#tablePns').DataTable();
            $('#tablePppk').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            // Inisialisasi select2
            $('#ketua_tim').select2({
                dropdownParent: $('#modalTambahTim'),
                width: '100%',
                placeholder: "-- Pilih Ketua Tim --"
            });

            // Event change bidang
            $('#bidang_id').on('change', function() {
                let bidangId = $(this).val();
                if (bidangId) {
                    $.ajax({
                        url: '/get-kepala-bidang/' + bidangId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#kepala_bidang').val(data.user_nama);
                            $('#kepala_bidang_id').val(data.user_id);
                        }
                    });
                } else {
                    $('#kepala_bidang').val('');
                    $('#kepala_bidang_id').val('');
                }
            });
        });
    </script>
    <script>
        const textarea = document.getElementById("timkerja_uraian");

        // Saat pertama kali user ketik
        textarea.addEventListener("input", function() {
            if (this.value.length === 1 && !this.value.startsWith("1. ")) {
                this.value = "1. " + this.value;
            }
        });

        // Saat tekan Enter
        textarea.addEventListener("keydown", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();

                let lines = this.value.split("\n").filter(l => l.trim() !== "");
                let nextNumber = lines.length + 1;

                this.value += "\n" + nextNumber + ". ";
            }
        });
    </script>
</body>

</html>
