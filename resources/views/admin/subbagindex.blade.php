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
                                    <th>Nama Sub Bagian</th>
                                    <th>Nama Bidang</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subbags as $no => $b)
                                <tr>
                                    <td>{{ $no+1 }}</td>
                                    <td>{{ $b->subbag_nama }}</td>
                                    <td>{{ $b->bidang->bidang_nama }}</td>
                                    <td>
                                        @if($b->subbag_status == '1')
                                        <span class="badge bg-success">Aktif</span>
                                        @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning btnEdit" data-id="{{ $b->subbag_id }}"
                                            data-link="{{ $b->subbag_link }}" data-bidang="{{ $b->subbag_bidang }}"
                                            data-nama="{{ $b->subbag_nama }}" data-status="{{ $b->subbag_status }}">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger btnHapus" data-id="{{ $b->subbag_id }}"
                                            data-nama="{{ $b->subbag_nama }}">
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
            <form action="{{ route('subbag.simpan') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahLabel">Tambah Data Bidang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Sub Bagian</label>
                            <input type="text" class="form-control" name="subbag_nama" required>
                        </div>
                        <div class="mb-3">
                            <label>Link Sub Bagian</label>
                            <input type="text" class="form-control" name="subbag_link" required>
                        </div>
                        <div class="mb-3">
                            <label>Bidang</label>
                            <select class="form-select" name="subbag_bidang" required>
                                @foreach ($bidangs as $bidang)
                                <option value="{{ $bidang->bidang_id }}">{{ $bidang->bidang_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-select" name="subbag_status" required>
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

    {{-- Modal Edit --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditLabel">Edit Data Sub Bagian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Sub Bagian</label>
                            <input type="text" class="form-control" name="subbag_nama" id="edit_nama" required>
                        </div>
                        <div class="mb-3">
                            <label>Link Sub Bagian</label>
                            <input type="text" class="form-control" name="subbag_link" id="edit_link" required>
                        </div>
                        <div class="mb-3">
                            <label>Bidang</label>
                            <select class="form-select" name="subbag_bidang" id="edit_bidang" required>
                                @foreach ($bidangs as $bidang)
                                <option value="{{ $bidang->bidang_id }}">{{ $bidang->bidang_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-select" name="subbag_status" id="edit_status" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update</button>
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
            let status = $(this).data('status');
            let link = $(this).data('link');
            let bidang = $(this).data('bidang');

            $('#edit_nama').val(nama);
            $('#edit_status').val(status);
            $('#edit_link').val(link);
            $('#edit_bidang').val(bidang);
            $('#formEdit').attr('action', '/subbag-update/' + id);

            $('#modalEdit').modal('show');
        });

        // Hapus Button
        $('.btnHapus').click(function() {
            let id = $(this).data('id');
            let nama = $(this).data('nama');

            $('#hapus_nama').text(nama);
            $('#formHapus').attr('action', '/subbag-hapus/' + id);

            $('#modalHapus').modal('show');
        });
    });
    </script>

</body>

</html>