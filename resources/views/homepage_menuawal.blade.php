<!DOCTYPE html>
<html lang="id">

<head>
    @include('partials.header')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        /* =========================
   SEARCH MENU SADARIN
   ========================= */
        .search-wrapper {
            max-width: 520px;
            margin: 0 auto 35px;
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            top: 50%;
            left: 18px;
            transform: translateY(-50%);
            color: #999;
            font-size: 1.2rem;
        }

        #menuSearch {
            width: 100%;
            padding: 14px 18px 14px 52px;
            font-size: 1.05rem;
            border-radius: 50px;
            border: 1.5px solid #ddd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .06);
            transition: all .25s ease;
        }

        #menuSearch:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, .15);
        }

        /* =========================
            GRID MENU
            ========================= */
        .grid-menu {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .menu-item {
            transition: all 0.25s ease;
        }

        .menu-item:hover {
            transform: translateY(-4px);
        }

        /* Flex untuk men-center menu */
        .grid-menu-center {
            display: flex;
            justify-content: center;
            /* center horizontal */
            flex-wrap: wrap;
            /* kalau banyak, pindah baris */
            gap: 20px;
            /* jarak antar menu */
        }

        .menu-box i {
            transition: transform .2s ease;
        }

        .menu-box:hover i {
            transform: scale(1.15);
        }
    </style>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap 5 JS (WAJIB supaya modal fade jalan) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="title">

            <h5 class="mb-4"><img src="{{ asset('assets/image/pemprov.png') }}" width="150"></h5>
            <h1>SADAR<span class="in">IN</span> <br></h1>
            <div style="font-size: 30px; color:#666;">Sistem Aplikasi Data dan Arsip Internal Dinas Kebudayaan</div>
        </div>
        @php
            $user = session('user_info');
        @endphp
        @if (session('error'))
            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="grid-menu-center mt-3">
            <!-- Coretax 2026 -->
            <button
                class="btn btn-primary open-upload-modal menu-box d-flex flex-column align-items-center justify-content-center text-center"
                data-title="Coretax 2026" data-route="{{ route('tambah.coretax.2026') }}" data-jenis="Coretax 2026"
                data-jenisfile="coretax2026">
                <i class="bi bi-file-earmark-text fs-1"></i>
                <span>Coretax 2026</span>
            </button>

            <!-- Laporan IKD -->
            <button
                class="btn btn-primary open-upload-modal menu-box d-flex flex-column align-items-center justify-content-center text-center"
                data-title="Laporan IKD" data-route="{{ route('tambah.laporan.ikd') }}" data-jenis="Laporan IKD"
                data-jenisfile="laporanikd">
                <i class="bi bi-file-bar-graph fs-1"></i>
                <span>Laporan IKD</span>
            </button>

            <!-- Perjanjian Kinerja 2026 -->
            <button
                class="btn btn-primary open-upload-modal menu-box d-flex flex-column align-items-center justify-content-center text-center"
                data-title="Perjanjian Kinerja 2026" data-route="{{ route('tambah.perjanjian.kinerja.2026') }}"
                data-jenis="Perjanjian Kinerja 2026" data-jenisfile="perjanjiankinerja2026">
                <i class="bi bi-file-check fs-1"></i>
                <span>Perjanjian Kinerja 2026</span>
            </button>
        </div>

        <div class="grid-menu-center mt-3">
            @if (!empty($user))
                <a href="#"
                    class="menu-box d-flex flex-column align-items-center justify-content-center text-center"
                    data-bs-toggle="modal" data-bs-target="#passwordConfirmModal">

                    <i class="bi bi-person-circle fs-1"></i>

                    <span class="fw-bold">PROFIL</span>

                    <span class="small text-muted mt-1">
                        {{ $user->user_nama }}
                    </span>
                </a>

                <!-- @if (empty($user->user_password))
<a href="#" class="menu-box bg-warning text-dark d-flex align-items-center gap-2"
                        data-bs-toggle="modal" data-bs-target="#setPasswordModal">
                        <i class="bi bi-shield-lock fs-1"></i>
                        <span>SET PASSWORD</span>
                    </a>
@else
<a href="#" class="menu-box bg-info text-white d-flex align-items-center gap-2"
                        data-bs-toggle="modal" data-bs-target="#setPasswordModal">
                        <i class="bi bi-key fs-1"></i>
                        <span>UBAH PASSWORD</span>
                    </a>
@endif -->
            @endif

            <a href="{{ route('arsip.disbud') }}"
                class="menu-box bg-success text-white d-flex align-items-center gap-2">
                <i class="bi bi-archive-fill fs-1"></i>
                <span>KE SADARIN</span>
            </a>
            <a href="{{ route('logout') }}" class="menu-box bg-danger text-white d-flex align-items-center gap-2">
                <i class="bi bi-box-arrow-right fs-1"></i>
                <span>KELUAR</span>
            </a>

        </div>

        {{-- ================================================= --}}
        {{-- MODAL PASSWORD --}}
        {{-- ================================================= --}}
        @if ($user)
            <div class="modal fade" id="passwordConfirmModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 border-0 shadow">

                        <div class="modal-header text-white" style="background-color: tomato;">
                            <h5 class="modal-title">
                                Verifikasi Password
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body p-4">

                            {{-- ========================= --}}
                            {{-- PASSWORD BELUM DISET --}}
                            {{-- ========================= --}}
                            @if (!$user->user_password)
                                <div class="text-center">

                                    <i class="bi bi-envelope-paper-fill" style="font-size:60px; color:tomato"></i>

                                    <h5 class="mt-3">
                                        Password Belum Diset
                                    </h5>

                                    <p class="text-muted">
                                        Password akan dikirim ke email Anda.
                                    </p>

                                    <form method="POST" action="{{ route('password.kirim.email') }}">
                                        @csrf
                                        <button type="submit" class="btn w-100"
                                            style="background-color: tomato; color:white;">
                                            Kirim Password ke Email
                                        </button>
                                    </form>

                                </div>

                                {{-- ========================= --}}
                                {{-- PASSWORD SUDAH ADA --}}
                                {{-- ========================= --}}
                            @else
                                <form method="POST" action="{{ route('akses.cek.profil') }}">
                                    @csrf

                                    <div class="text-center mb-3">
                                        <i class="bi bi-shield-lock fs-1 text-primary"></i>
                                        <h6 class="mt-2">
                                            {{ $user->user_nama }}
                                        </h6>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            Masukkan Password
                                        </label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        Masuk
                                    </button>

                                    <div class="text-center mt-3 btn btn-primary w-100" style="background-color: tomato; border:none;">
                                        <a href="{{ route('akses.reset.password') }}"
                                            class="small text-white text-decoration-none">
                                            Reset Password
                                        </a>
                                    </div>

                                </form>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="modal fade" id="setPasswordModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('password.send.link') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Pengaturan Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body text-center">
                            <p>
                                Link pengaturan password akan dikirim ke email Anda:
                                <br>
                                <strong>{{ $user->user_email ?? '-' }}</strong>
                            </p>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">
                                Kirim Link ke Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL UNIVERSAL UPLOAD BERKAS -->
        <div class="modal fade" id="modalUploadBerkas" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="formUploadBerkas" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="user_nip" value="{{ $user->user_nip }}">
                        <input type="hidden" name="user_nik" value="{{ $user->user_nik }}">
                        <input type="hidden" name="kumpulan_jenis" id="kumpulan_jenis">
                        <input type="hidden" name="jenisfile" id="jenisfile">
                        <input type="hidden" name="user_jeniskerja" value="{{ $user->user_jeniskerja }}">

                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Upload Berkas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-header" id="tanggalMelaporWrapper" style="display:none;">
                            <label class="form-label">Tanggal Melapor Pajak</label>
                            <label class="form-label text-danger fw-bold">(Bukan Tanggal Upload Bukti)</label>
                            <input type="date" name="tanggal_melapor" id="tanggal_melapor" class="form-control">
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label id="labelFile" class="form-label">File</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer class="text-center mt-4 py-3">
            <div>
                &copy; {{ date('Y') }} <strong>Dinas Kebudayaan Provinsi Bali</strong> —
                <span class="text-gray fw-bold">SADARIN</span>
            </div>
            <div class="mt-1">
                <i class="bi bi-code-slash"></i> Dibuat oleh
                <span class="text-danger mx-1"><i class="bi bi-heart-fill"></i></span>
                <span class="text-gray fw-bold">PRANATA KOMPUTER AHLI PERTAMA</span>
            </div>
        </footer>
    </div>
    @if (session('open_modal'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var modal = new bootstrap.Modal(document.getElementById("modal-{{ session('open_modal') }}"));
                modal.show();
            });
        </script>
    @endif
</body>
<!-- Bootstrap Select CSS -->
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

<!-- jQuery (wajib untuk bootstrap-select) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap Select JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>



<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const searchInput = document.getElementById('menuSearch');
    const menuItems = document.querySelectorAll('.menu-item');

    searchInput.addEventListener('input', function() {
        const keyword = this.value.toLowerCase();

        menuItems.forEach(item => {
            const text = item.innerText.toLowerCase();

            if (text.includes(keyword)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // open-upload-modal
        document.querySelectorAll('.open-upload-modal').forEach(btn => {
            btn.addEventListener('click', function() {

                const title = this.dataset.title;
                const route = this.dataset.route;
                const jenis = this.dataset.jenis;
                const jenisfile = this.dataset.jenisfile;

                document.getElementById('modalTitle').innerText = title;
                document.getElementById('formUploadBerkas').action = route;
                document.getElementById('kumpulan_jenis').value = jenis;
                document.getElementById('jenisfile').value = jenisfile;

                // ==============================
                // TAMBAHAN KHUSUS CORETAX
                // ==============================
                const tanggalWrapper = document.getElementById('tanggalMelaporWrapper');
                const tanggalInput = document.getElementById('tanggal_melapor');

                if (jenisfile === 'coretax2026') {
                    tanggalWrapper.style.display = 'block';
                    tanggalInput.required = true;
                } else {
                    tanggalWrapper.style.display = 'none';
                    tanggalInput.required = false;
                    tanggalInput.value = '';
                }

            });
        });
    });
</script>
<script>
    $(document).on('click', '.open-upload-modal', function() {
        const title = $(this).data('title');
        const route = $(this).data('route');
        const jenis = $(this).data('jenis');
        const jenisfile = $(this).data('jenisfile');

        $('#modalTitle').text(title);
        $('#labelFile').text(title);
        $('#kumpulan_jenis').val(jenis);
        $('#jenisfile').val(jenisfile);
        $('#formUploadBerkas').attr('action', route);

        new bootstrap.Modal(document.getElementById('modalUploadBerkas')).show();
    });
</script>

</html>
