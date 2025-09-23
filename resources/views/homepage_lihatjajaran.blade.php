<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SADARIN - Sistem Data dan Arsip Internal</title>
    <link rel="icon" href="{{ asset('assets/image/pemprov.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 1600px;
            margin-top: 40px;
            background: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            padding: 40px;
        }

        .title h1 {
            font-weight: bold;
            font-size: 50px;
            line-height: 1.2;
        }

        .title .in {
            color: orangered;
        }

        /* Struktur Organisasi */
        .org-chart {
            margin-top: 60px;
            position: relative;
        }

        .org-node {
            display: inline-block;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 15px;
            background: #fff;
            min-width: 180px;
            margin: 20px auto;
            text-align: center;
            position: relative;
        }

        .org-node img {
            width: 90px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #666;
            margin-bottom: 10px;
            background: #fff;
        }

        .org-node {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 15px;
            background: #fff;
            width: 400px;
            /* FIXED WIDTH untuk semua kotak sama */
            height: 350px;
            /* optional, agar tinggi juga sama */
            margin: 20px auto;
            text-align: center;
            position: relative;
            box-sizing: border-box;
        }

        .org-node img {
            width: 90px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #666;
            margin-bottom: 10px;
            background: #fff;
        }

        .org-node h6,
        .org-node small {
            margin: 2px 0;
            word-wrap: break-word;
        }

        /* Garis vertikal dari induk ke anak */
        .org-connector-vertical {
            width: 2px;
            height: 40px;
            background: #ccc;
            margin: 0 auto;
        }

        .org-children {
            display: flex;
            justify-content: center;
            gap: 60px;
            flex-wrap: nowrap;
            margin-top: 20px;
            position: relative;
        }

        /* Garis horizontal penghubung antar anak */
        .org-children::before {
            content: "";
            position: absolute;
            top: -20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #ccc;
        }

        /* Garis kecil dari kotak ke garis horizontal */
        .org-children .org-node::before {
            content: "";
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 20px;
            background: #ccc;
        }

        footer {
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container text-center">
        <!-- Header -->
        <div class="title">
            <h5 class="mb-4">
                <img src="{{ asset('assets/image/pemprov.png') }}" width="150">
            </h5>
            <h1>SADAR<span class="in">IN</span></h1>
            <div style="font-size: 22px; color: #666;">
                Sistem Aplikasi Data dan Arsip Internal Dinas Kebudayaan Provinsi Bali
            </div>
            <div style="font-size: 40px; color: #666;">
                <strong>Struktur Organisasi</strong>
            </div>
        </div>

        {{-- Kepala Atas --}}
        @if($kepalaAtas)
        <div class="org-node mx-auto">
            <h4 class="fw-bold">{{ $kepalaAtas->jabatan_nama }}</h4>
            <h6 class="fw-bold">{{ $kepalaAtas->bidang_nama }}</h6>
            <br>
            <img src="{{ $kepalaAtas->user_foto && $kepalaAtas->user_foto != '-' ? asset($kepalaAtas->user_foto) : asset('assets/image/pemprov.png') }}"
                alt="Foto">
            <h6 class="fw-bold">{{ $kepalaAtas->user_nama }}</h6>
            <small class="fw-bold">NIP. {{ $kepalaAtas->user_nip }}</small>
            <h6 class="fw-bold" style="font-size: 13px;">
                {{ $kepalaAtas->golongan_nama. ' - ' . $kepalaAtas->golongan_pangkat}}
            </h6>
        </div>
        <div class="org-connector-vertical"></div>
        @endif

        {{-- Kepala Sejajar --}}
        @if($kepalaSejajar && $kepalaSejajar->count() > 0)
        <div class="org-children">
            @foreach($kepalaSejajar as $kepala)
            <div class="org-node">
                <h4 class="fw-bold">{{ $kepala->jabatan_nama }}</h4>
                <h6 class="fw-bold">{{ $kepala->bidang_nama }}</h6>
                <br>
                <img src="{{ $kepala->user_foto && $kepala->user_foto != '-' ? asset($kepala->user_foto) : asset('assets/image/pemprov.png') }}"
                    alt="Foto">
                <h6 class="fw-bold">
                    {{ $kepala->user_gelardepan && $kepala->user_gelardepan != '-' ? $kepala->user_gelardepan . ' ' : '' }}
                    {{ $kepala->user_nama }}
                    {{ $kepala->user_gelarbelakang && $kepala->user_gelarbelakang != '-' ? ',' . $kepala->user_gelarbelakang : '' }}
                </h6>
                <small class="fw-bold">NIP. {{ $kepala->user_nip }}</small>
                <h6 class="fw-bold" style="font-size: 13px;">
                    {{ $kepala->golongan_nama. ' - ' . $kepala->golongan_pangkat}}
                </h6>
            </div>
            @endforeach
        </div>
        <div class="org-connector-vertical"></div>
        @endif

        @php
        $staff = collect($staff); // pastikan Collection
        $chunks = $staff->chunk(3); // bagi setiap 3 staff jadi 1 baris
        @endphp
        @foreach($chunks as $index => $chunk)
        <div class="org-children">
            @foreach($chunk as $s)
            <div class="org-node">
                <h4 class="fw-bold">{{ $s->jabatan_nama }}</h4>
                <h6 class="fw-bold">{{ $s->bidang_nama }}</h6>
                <br>
                <img src="{{ $s->user_foto && $s->user_foto != '-' ? asset($s->user_foto) : asset('assets/image/pemprov.png') }}"
                    alt="Foto">
                <h6 class="fw-bold">
                    {{ $s->user_gelardepan && $s->user_gelardepan != '-' ? $s->user_gelardepan . ' ' : '' }}
                    {{ $s->user_nama }}
                    {{ $s->user_gelarbelakang && $s->user_gelarbelakang != '-' ? ',' . $s->user_gelarbelakang : '' }}
                </h6>
                <small class="fw-bold">NIP. {{ $s->user_nip }}</small>
                <h6 class="fw-bold" style="font-size: 13px;">
                    {{ $s->golongan_nama. ' - ' . $s->golongan_pangkat}}
                </h6>
            </div>
            @endforeach
        </div>
        @if($index < $chunks->count() - 1)
            <div class="org-connector-vertical"></div>
            @endif
            @endforeach



            <!-- Footer -->
            <footer class="text-center mt-5 py-4">
                <div>
                    &copy; {{ date('Y') }} <strong>Dinas Kebudayaan Provinsi Bali</strong> —
                    <span class="fw-bold">SADARIN</span>
                </div>
                <div class="mt-1">
                    <i class="bi bi-code-slash"></i> Dibuat oleh
                    <span class="text-danger mx-1"><i class="bi bi-heart-fill"></i></span>
                    <span class="text-dark">Pranata Komputer Ahli Pertama</span>
                </div>
            </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>