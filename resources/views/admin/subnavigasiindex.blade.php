<!DOCTYPE html>
<html lang="id">

<head>
    @include('admin.partials.headadmin')

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            @include('admin.partials.sidebaradmin')

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

                {{-- Tabel Data Bidang --}}
                <div class="card">
                    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                        <span>Data Bidang</span>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <i class="bi bi-plus-lg"></i> Tambah Data
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="tabelBidang" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Navigasi</th>
                                    <th>Nama Sub Bagian</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subnavigasisekretariat as $no => $b)
                                <tr>
                                    <td>{{ $no+1 }}</td>
                                    <td>{{ $b->subnavigasisekre_nama }}</td>
                                    <td>{{ $b->navigasisekretariat->navigasisekre_nama}}</td>

                                    <td>
                                        @if($b->navigasisekretariat->navigasisekre_status == '1')
                                        <span class="badge bg-success">Aktif</span>
                                        @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning btnEdit"
                                            data-id="{{ $b->subnavigasisekre_id }}"
                                            data-navigasi="{{ $b->subnavigasisekre_navigasisekre }}"
                                            data-link="{{ $b->subnavigasisekre_link }}"
                                            data-nama="{{ $b->subnavigasisekre_nama }}"
                                            data-status="{{ $b->subnavigasisekre_status }}">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger btnHapus"
                                            data-id="{{ $b->subnavigasisekre_id }}"
                                            data-nama="{{ $b->subnavigasisekre_nama }}">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Footer --}}
                @include('admin.partials.footeradmin')
            </main>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('subnavigasi.simpan') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahLabel">Tambah Data Sub Navigasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Sub Navigasi</label>
                            <input type="text" class="form-control" name="subnavigasisekre_nama" required>
                        </div>
                        <div class="mb-3">
                            <label>Sub Navigasi Link</label>
                            <input type="text" class="form-control" name="subnavigasisekre_link" required>
                        </div>
                        <div class="mb-3">
                            <label>Navigasi</label>
                            <select class="form-select" name="subnavigasisekre_navigasisekre" required>
                                @foreach ($navs as $a)
                                <option value="{{ $a->navigasisekre_id }}">{{ $a->navigasisekre_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <label>Status</label>
                                <select class="form-select" name="subnavigasisekre_status" required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditLabel">Edit Data Sub Navigasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Sub Navigasi</label>
                            <input type="text" class="form-control" name="subnavigasisekre_nama" id="edit_nama"
                                required>
                        </div>
                        <div class="mb-3">
                            <label>Sub Navigasi Link</label>
                            <input type="text" class="form-control" name="subnavigasisekre_link" id="edit_link"
                                required>
                        </div>
                        <div class="mb-3">
                            <label>Navigasi</label>
                            <select class="form-select" name="subnavigasisekre_navigasisekre" id="edit_navigasi"
                                required>
                                @foreach ($navs as $a)
                                <option value="{{ $a->navigasisekre_id }}">{{ $a->navigasisekre_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-select" name="subnavigasisekre_status" id="edit_status" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Hapus --}}
    <div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formHapus" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalHapusLabel">Hapus Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Yakin ingin menghapus data <b id="hapus_nama"></b>?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- jQuery & DataTables --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Init DataTable
            $('#tabelBidang').DataTable({
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                }
            });

            // Edit Button
            $('.btnEdit').click(function() {
                let id = $(this).data('id');
                let nama = $(this).data('nama');
                let link = $(this).data('link');
                let navigasi = $(this).data('navigasi');
                let status = $(this).data('status');

                // isi form modal edit
                $('#edit_nama').val(nama);
                $('#edit_link').val(link);
                $('#edit_navigasi').val(navigasi);
                $('#edit_status').val(status);

                // set action form
                $('#formEdit').attr('action', '/subnavigasi-update/' + id);

                // tampilkan modal
                $('#modalEdit').modal('show');
            });

            // Hapus Button
            $('.btnHapus').click(function() {
                let id = $(this).data('id');
                let nama = $(this).data('nama');

                $('#hapus_nama').text(nama);
                $('#formHapus').attr('action', '/subnavigasi-hapus/' + id);

                $('#modalHapus').modal('show');
            });
        });
    </script>

</body>

</html>