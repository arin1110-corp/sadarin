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
    </style>
</head>

<body>
    <div class="container">
        <div class="title">

            <h5 class="mb-4"><img src="{{ asset('assets/image/pemprov.png') }}" width="150"></h5>
            <h1>SADAR<span class="in">IN</span> <br></h1>
            <div style="font-size: 30px; color:#666;">Sistem Aplikasi Data dan Arsip Internal Dinas Kebudayaan</div>
        </div>

        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" id="menuSearch" placeholder="Cari bidang, layanan, atau menu..." autocomplete="off">
        </div>

        <div class="grid-menu-center mt-3">
            @if (!empty($user))
                <a href="/detail-pegawai" class="menu-box-nama nama-menucek">
                    {{ $user->user_nip }}<br>{{ $user->user_nik }}<br>{{ $user->user_nama }}<br>{{ $user->jabatan_nama }}
                </a>
            @endif
            <a href="{{ route('struktur.organisasi') }}" class="menu-box-struktur" target="_blank">
                Struktur Organisasi
            </a>
        </div>
        <br>
        <div class="grid-menu mt-3">
            @if ($bidang && $bidang->count() > 0)
                @foreach ($bidang as $b)
                    <a href="{{ route($b->bidang_link) }}" class="menu-box">
                        {{ $b->bidang_nama }}
                    </a>
                @endforeach
            @endif
        </div>
        <br>
        <div class="grid-menu-center mt-3">
            <a href="https://drive.google.com/drive/folders/1o_3gTGKUyaWOKNvxzZ80KB7hqXSRoMTQ" class="menu-box"
                target="_blank">
                UPLOAD DATA
            </a>
        </div>

        <footer class="text-center mt-4 py-3">
            <div>
                &copy; {{ date('Y') }} <strong>Dinas Kebudayaan Provinsi Bali</strong> —
                <span class="text-warning fw-bold">SADARIN</span>
            </div>
            <div class="mt-1">
                <i class="bi bi-code-slash"></i> Dibuat oleh
                <span class="text-danger mx-1"><i class="bi bi-heart-fill"></i></span>
                <span class="text-light">Pranata Komputer Ahli Pertama</span>
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
