<!DOCTYPE html>
<html lang="id">

<head>
    @include('partials.header')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <div class="container">
        <div class="title">

            <h5 class="mb-4"><img src="{{asset('assets/image/pemprov.png')}}" width="150"></h5>
            <h1>SADAR<span class="in">IN</span> <br></h1>
            <div style="font-size: 30px; color:#666;">Sistem Data dan Arsip Internal Dinas Kebudayaan</div>
        </div>

        <div class="grid-menu">
            <a href="{{ route('data.sekretariat') }}" class="menu-box">Sekretariat</a>
            <a href="#" class="menu-box">Bidang Kesenian</a>
            <a href="#" class="menu-box">Bidang Cagar Budaya dan Permuseuman</a>
            <a href="#" class="menu-box">Bidang Dokumentasi dan Publikasi</a>
            <a href="#" class="menu-box">Bidang Tenaga dan Warisan Budaya</a>
            <a href="#" class="menu-box">UPTD Museum Bali</a>
            <a href="#" class="menu-box">UPTD Monumen Perjuangan Rakyat Bali</a>
            <a href="#" class="menu-box">UPT Taman Budaya</a>
            <a href="{{ route('data.upload') }}" class="menu-box">
                <div class="bi bi-cloud-upload">&nbspUPLOAD DATA</div>
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

</html>