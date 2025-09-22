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

        <!-- Struktur Organisasi -->
        <div class="org-chart">

            <!-- Kepala Dinas -->
            @php
            $kadis = $dataPegawai->where('user_jabatan', '20')->first();
            @endphp
            @if($kadis)
            <div class="org-node mx-auto">
                <h4 class="fw-bold">Kepala Dinas</h4>
                <h6 class="fw-bold">Dinas Kebudayaan Provinsi Bali</h6>
                <br>
                <img src="{{ $kadis->user_foto && $kadis->user_foto != '-' ? asset($kadis->user_foto) : asset('assets/image/pemprov.png') }}"
                    alt="Foto">
                <h6 class="fw-bold" style="font-size: 15px;">{{ $kadis->user_nama }}</h6>
                <h6 class="fw-bold" style="font-size: 13px;">NIP. {{ $kadis->user_nip }}</h6>
                <h6 class="fw-bold" style="font-size: 13px;">
                    {{ $kadis->golongan_nama. ' - ' . $kadis->golongan_pangkat}}
                </h6>
                @endif
            </div>

            <!-- Garis ke bawah dari Kadis -->
            <div class="org-connector-vertical"></div>

            <!-- Baris 1: Sekretaris + Kabid 1-3 -->
            <div class="org-children">
                @php
                $sekdis = $dataPegawai->where('user_jabatan', 54)->first(); // Sekretaris
                @endphp

                @if($sekdis)
                <div class="org-node">
                    <h4 class="fw-bold">{{ $sekdis->jabatan_nama }} Dinas</h4>
                    <h6 class="fw-bold">Kebudayaan Provinsi Bali</h6>
                    <br>
                    <img src="{{ $sekdis->user_foto && $sekdis->user_foto != '-' ? asset($sekdis->user_foto) : asset('assets/image/pemprov.png') }}"
                        alt="Foto">
                    <div class="fw-bold" style="font-size: 15px;">
                        {{ $sekdis->user_gelardepan && $sekdis->user_gelardepan != '-' ? $sekdis->user_gelardepan . ' ' : '' }}
                        {{ $sekdis->user_nama }}
                        {{ $sekdis->user_gelarbelakang && $sekdis->user_gelarbelakang != '-' ? ',' . $sekdis->user_gelarbelakang : '' }}
                    </div>
                    <div class="fw-bold" style="font-size: 13px;">NIP. {{ $sekdis->user_nip }}</div>
                    <div class="fw-bold" style="font-size: 13px;">
                        {{ $sekdis->golongan_nama. ' - ' . $sekdis->golongan_pangkat}}
                    </div>
                    <div>
                        <a href="{{ route('lihat.jajaran', ['id' => $sekdis->user_bidang]) }}">
                            <button class="btn btn-sm btn-success mt-2">Lihat Jajaran</button>
                        </a>
                    </div>
                </div>
                @endif

                @php
                $dataKabid = $dataPegawai->where('user_jabatan', 19); // Kepala Bidang
                $kabidPertama = $dataKabid->take(2); // 2 pertama untuk baris atas
                $kabidSisanya = $dataKabid->slice(2); // sisanya pindah ke baris bawah
                @endphp

                @foreach($kabidPertama as $kabid)
                <div class="org-node">
                    <!-- Kepala Bidang 1-2 -->
                    <h4 class="fw-bold">{{ $kabid->jabatan_nama }}</h4>
                    <h6 class="fw-bold">{{ $kabid->bidang_nama }}</h6>
                    <br>
                    <img src="{{ $kabid->user_foto && $kabid->user_foto != '-' ? asset($kabid->user_foto) : asset('assets/image/pemprov.png') }}"
                        alt="Foto">
                    <h6 class="fw-bold" style="font-size: 15px;">
                        {{ $kabid->user_gelardepan && $kabid->user_gelardepan != '-' ? $kabid->user_gelardepan . ' ' : '' }}
                        {{ $kabid->user_nama }}
                        {{ $kabid->user_gelarbelakang && $kabid->user_gelarbelakang != '-' ? ',' . $kabid->user_gelarbelakang : '' }}
                    </h6>
                    <h6 class="fw-bold" style="font-size: 13px;">NIP. {{ $kabid->user_nip }}</h6>
                    <h6 class="fw-bold" style="font-size: 13px;">
                        {{ $kabid->golongan_nama. ' - ' . $kabid->golongan_pangkat}}
                    </h6>
                    <div>
                        <a href="{{ route('lihat.jajaran', ['id' => $kabid->user_bidang]) }}">
                            <button class="btn btn-sm btn-success mt-2">Lihat Jajaran</button>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Baris 2: Kepala Bidang sisanya -->
            @if($kabidSisanya->count() > 0)
            <div class="org-children">
                @foreach($kabidSisanya as $kabid)
                <div class="org-node">
                    <!-- Kepala Bidang 3 dst -->
                    <h4 class="fw-bold">{{ $kabid->jabatan_nama }}</h4>
                    <h6 class="fw-bold">{{ $kabid->bidang_nama }}</h6>
                    <br>
                    <img src="{{ $kabid->user_foto && $kabid->user_foto != '-' ? asset($kabid->user_foto) : asset('assets/image/pemprov.png') }}"
                        alt="Foto">
                    <h6 class="fw-bold" style="font-size: 15px;">
                        {{ $kabid->user_gelardepan && $kabid->user_gelardepan != '-' ? $kabid->user_gelardepan . ' ' : '' }}
                        {{ $kabid->user_nama }}
                        {{ $kabid->user_gelarbelakang && $kabid->user_gelarbelakang != '-' ? ',' . $kabid->user_gelarbelakang : '' }}
                    </h6>
                    <h6 class="fw-bold" style="font-size: 13px;">NIP. {{ $kabid->user_nip }}</h6>
                    <h6 class="fw-bold" style="font-size: 13px;">
                        {{ $kabid->golongan_nama. ' - ' . $kabid->golongan_pangkat}}
                    </h6>
                    <div>
                        <a href="{{ route('lihat.jajaran', ['id' => $kabid->user_bidang]) }}">
                            <button class="btn btn-sm btn-success mt-2">Lihat Jajaran</button>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif



            <!-- Baris 2: Kabid 4-7 -->
            <div class="org-children">

                @php
                $dataKauptd = $dataPegawai->where('user_jabatan', 29); // Kepala UPTD
                @endphp

                @foreach($dataKauptd as $kauptd)<div class="org-node">
                    <!-- Kepala UPTD -->
                    <h4 class="fw-bold">{{ $kauptd->jabatan_nama }}</h4>
                    <h6 class="fw-bold">{{ $kauptd->bidang_nama }}</h6>
                    <br>
                    <img src="{{ $kauptd->user_foto && $kauptd->user_foto != '-' ? asset($kauptd->user_foto) : asset('assets/image/pemprov.png') }}"
                        alt="Foto">
                    <h6 class="fw-bold" style="font-size: 15px;">
                        {{ $kauptd->user_gelardepan && $kauptd->user_gelardepan != '-' ? $kauptd->user_gelardepan . ' ' : '' }}
                        {{ $kauptd->user_nama }}
                        {{ $kauptd->user_gelarbelakang && $kauptd->user_gelarbelakang != '-' ? ',' . $kauptd->user_gelarbelakang : '' }}
                    </h6>
                    <h6 class="fw-bold" style="font-size: 13px;">NIP. {{ $kauptd->user_nip }}</h6>
                    <h6 class="fw-bold" style="font-size: 13px;">
                        {{ $kauptd->golongan_nama. ' - ' . $kauptd->golongan_pangkat}}
                    </h6>
                    <div>
                        <a href="{{ route('lihat.jajaran', ['id' => $kauptd->user_bidang]) }}">
                            <button class="btn btn-sm btn-success mt-2">Lihat Jajaran</button>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Baris 2: Kabid 4-7 -->
            <div class="org-children">
                <div class="org-node">
                    <img src="{{ asset('assets/image/pemprov.png') }}" alt="Foto">
                    <h6 class="fw-bold">Jabatan Fungsional</h6>
                    <h6 class="fw-bold"></h6>
                    <small>Seluruh Pegawai Fungsional Pada Dinas Kebudayaan Provinsi Bali</small>
                    <div>
                        <a href="{{ route('lihat.jajaran', ['kategori' => 'Fungsional']) }}">
                            <button class="btn btn-sm btn-success mt-2">Lihat Jajaran</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

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