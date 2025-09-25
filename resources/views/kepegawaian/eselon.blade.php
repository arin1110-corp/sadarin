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
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-layers me-2"></i> Data Eselon</h5>
                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Data
                        </button>
                    </div>
                    {{-- notifikasi --}}
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    {{-- end notifikasi --}}
                </div>

                {{-- edit modal --}}

                <!-- tambah data modal -->
                <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="tambahDataModalLabel">Tambah Data Eselon</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('kepegawaian.tambah.eselon') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="eselon_nama" class="form-label">Nama Eselon</label>
                                        <input type="text" class="form-control" id="eselon_nama" name="eselon_nama"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="eselon_status" class="form-label">Status</label>
                                        <select class="form-select" id="eselon_status" name="eselon_status" required>
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
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
                                    <th>Nama Eselon</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($eselons as $no => $user)
                                <tr>
                                    <td class="text-center">{{ $no + 1 }}</td>
                                    <td>{{ $user->eselon_nama }}</td>
                                    <td class="text-center">
                                        @if($user->eselon_status == '1')
                                        <span class="badge bg-success">Aktif</span>
                                        @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit{{ $user->eselon_id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#modalHapus{{ $user->eselon_id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>

        {{-- modal edit --}}
        @foreach($eselons as $user)
        <div class="modal fade" id="modalEdit{{ $user->eselon_id }}" tabindex="-1"
            aria-labelledby="modalEditLabel{{ $user->eselon_id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditLabel{{ $user->eselon_id }}">Edit Data Eselon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('kepegawaian.edit.eselon') }}" method="POST">
                        @csrf
                        <input type="hidden" name="eselon_id" value="{{ $user->eselon_id }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="eselon_nama_{{ $user->eselon_id }}" class="form-label">Eselon Nama</label>
                                <input type="text" class="form-control" id="eselon_nama_{{ $user->eselon_id }}"
                                    name="eselon_nama" value="{{ $user->eselon_nama }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="eselon_status_{{ $user->eselon_id }}" class="form-label">Status</label>
                                <select class="form-select" id="eselon_status_{{ $user->eselon_id }}"
                                    name="eselon_status" required>
                                    <option value="1" {{ $user->eselon_status == '1' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="0" {{ $user->eselon_status == '0' ? 'selected' : '' }}>Tidak
                                        Aktif
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

        {{-- modal hapus --}}
        @foreach($eselons as $user)
        <div class="modal fade" id="modalHapus{{ $user->eselon_id }}" tabindex="-1"
            aria-labelledby="modalHapusLabel{{ $user->eselon_id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalHapusLabel{{ $user->eselon_id }}">Hapus Data Eselon
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('kepegawaian.hapus.eselon') }}" method="POST">
                        @csrf
                        <input type="hidden" name="eselon_id" value="{{ $user->eselon_id }}">
                        <input type="hidden" name="action" value="delete">
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menghapus Data Eselon
                                <strong>{{ $user->eselon_nama }}</strong>?
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
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