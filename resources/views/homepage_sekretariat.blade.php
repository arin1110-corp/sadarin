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
            <div style="font-size: 30px; color:#666;">Sistem Aplikasi Data dan Arsip Internal Dinas Kebudayaan</div>
        </div>

        <div class="grid-menu">
            <a href="{{ route('data.ppep') }}" class="menu-box">Bagian Penyusunan Program Evaluasi dan Pelaporan
                (PPEP)</a>
            <a href="{{ route('data.keuangan') }}" class="menu-box">Bagian Keuangan</a>
            <a href="{{ route('data.umpeg') }}" class="menu-box">Bagian Umum dan Kepegawaian</a>
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