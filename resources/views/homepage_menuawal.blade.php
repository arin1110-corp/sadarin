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

        <div class="grid-menu-center mt-3">

            @if (!empty($user))

                <a href="#"
                    class="menu-box d-flex flex-column align-items-center justify-content-center text-center"
                    data-bs-toggle="modal" data-bs-target="#passwordConfirmModal">

                    <i class="bi bi-person-circle fs-1 mb-2"></i>

                    <span class="fw-bold">PROFIL</span>

                    <span class="small text-muted mt-1">
                        {{ $user->user_nama }}
                    </span>

                </a>

                @if (empty($user->password))
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
                @endif

            @endif

            <a href="{{ route('arsip.disbud') }}"
                class="menu-box bg-success text-white d-flex align-items-center gap-2">
                <i class="bi bi-archive-fill fs-1"></i>
                <span>LIHAT ARSIP DISBUD</span>
            </a>

        </div>

        <div class="modal fade" id="passwordConfirmModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('akses.cek.profil') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Masukkan Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <input type="password" name="password" class="form-control"
                                placeholder="Masukkan Password Anda" required>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Masuk Profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

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
</body>

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


</html>
