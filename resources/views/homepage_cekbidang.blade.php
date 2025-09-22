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
            @if(isset($user))
            <a href="/detail-pegawai" class="menu-box-nama nama-menucek">
                {{ $user->user_nip }}<br>{{ $user->user_nama }}<br>{{ $user->jabatan_nama }}
            </a>
            @endif
            <a href="{{ route('struktur.organisasi') }}" class="menu-box-struktur" target="_blank">
                Struktur Organisasi
            </a>
            @if($bidang && $bidang->count() > 0)
            @foreach ($bidang as $b)
            <a href="{{ route($b->bidang_link) }}" class="menu-box">
                {{ $b->bidang_nama }}
            </a>
            @endforeach
            @endif
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

</html>