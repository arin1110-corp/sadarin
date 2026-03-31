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
                        <span>Data Mapping Tombol Berkas</span>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <i class="bi bi-plus-lg"></i> Tambah Data
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="tabelMappingTombolBerkas" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Mapping Tombol Berkas</th>
                                    <th>Prefix</th>
                                    <th>Link JSON</th>
                                    <th>Jenis Kerja</th>
                                    <th>Link Drive</th>
                                    <th>Folder</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mappings as $no => $b)
                                <tr>
                                    <td>{{ $no+1 }}</td>
                                    <td>{{ $b->tombol_nama }}</td>
                                    <td>{{ $b->tombol_prefix }}</td>
                                    <td>{{ $b->json_nama }}</td>
                                    <td>@if ($b->mapping_jeniskerja == 1) PNS
                                        @elseif($b->mapping_jeniskerja == 2) PPPK 
                                        @elseif($b->mapping_jeniskerja == 3) PPPK Paruh Waktu 
                                        @elseif($b->mapping_jeniskerja == 4) PJLP @endif</td>
                                    <td>{{ $b->mapping_folderid }}</td>
                                    <td>{{ $b->mapping_folder }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning btnEdit" data-id="{{ $b->mapping_id }}" 
                                            data-tombol="{{ $b->mapping_tombol}}"
                                            data-jeniskerja="{{ $b->mapping_jeniskerja}}" data-folderid="{{ $b->mapping_folderid }}" 
                                            data-folder="{{ $b->mapping_folder }}">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger btnHapus" data-id="{{ $b->mapping_id }}"
                                            data-nama="{{ $b->mapping_tombol }}">
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
            <form action="{{ route('mappingtombol.simpan') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahLabel">Tambah Data Mapping Tombol Berkas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tombol</label>
                            <select class="form-select" name="mapping_tombol" required>
                                <option value="">Pilih Tombol</option>
                                @foreach ($tombols as $j)
                                <option value="{{ $j->tombol_id }}">{{ $j->tombol_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Jenis Kerja</label>
                            <select class="form-select" name="mapping_jeniskerja" required>
                                <option value="">Pilih Jenis Kerja</option>
                                <option value="1">PNS</option>
                                <option value="2">PPPK</option>
                                <option value="3">PPPK Paruh Waktu</option>
                                <option value="4">PJLP</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>ID Link Drive</label>
                            <input type="text" class="form-control" name="mapping_folderid" required>
                        </div>
                        <div class="mb-3">
                            <label>Folder</label>
                            <input type="text" class="form-control" name="mapping_folder" required>
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
                        <h5 class="modal-title" id="modalEditLabel">Edit Data Mapping Tombol Berkas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tombol</label>
                            <select class="form-select" name="mapping_tombol" id="edit_tombol" required>
                                <option value="">Pilih Tombol</option>
                                @foreach ($tombols as $j)
                                <option value="{{ $j->tombol_id }}">{{ $j->tombol_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Jenis Kerja</label>
                            <select class="form-select" name="mapping_jeniskerja" id="edit_jeniskerja" required>
                                <option value="">Pilih Jenis Kerja</option>
                                <option value="1">PNS</option>
                                <option value="2">PPPK</option>
                                <option value="3">PPPK Paruh Waktu</option>
                                <option value="4">PJLP</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>ID Link Drive</label>
                            <input type="text" class="form-control" name="mapping_folderid" id="edit_folderid" required>
                        </div>
                        <div class="mb-3">
                            <label>Folder</label>
                            <input type="text" class="form-control" name="mapping_folder" id="edit_folder" required>
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
        $('#tabelmappingTombolBerkas').DataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
            }
        });

        // Edit Button
        $('.btnEdit').click(function() {
            let id = $(this).data('id');
            let tombol = $(this).data('tombol');
            let jeniskerja = $(this).data('jeniskerja');
            let folder = $(this).data('folder');
            let folderid = $(this).data('folderid');

            $('#edit_tombol').val(tombol);
            $('#edit_jeniskerja').val(jeniskerja);
            $('#edit_folder').val(folder);
            $('#edit_folderid').val(folderid);
            $('#formEdit').attr('action', '/admin/mapping-tombol/update/' + id);
            $('#modalEdit').modal('show');
        });
        

        // Hapus Button
        $('.btnHapus').click(function() {
            let id = $(this).data('id');
            let nama = $(this).data('nama');

            $('#hapus_nama').text(nama);
            $('#formHapus').attr('action', '/admin/mapping-tombol/delete/' + id);

            $('#modalHapus').modal('show');
        });
    });
    </script>

</body>

</html>