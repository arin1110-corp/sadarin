<!DOCTYPE html>
<html lang="en">

<head>
    <title>SADARIN - Sistem Data dan Arsip Internal</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem Data dan Arsip Internal - SADARIN">
    <meta name="keywords"
        content="SADARIN, Sistem Data dan Arsip Internal, Data, Arsip, Sistem Informasi, Pengelolaan Data, Dinas Kebudayaan">
    <meta name="author" content="SADARIN Team">
    <link rel="icon" href="{{ asset('assets/image/pemprov.png') }}" type="image/x-icon">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            background: #f4f6f9;
            display: flex;
            flex-direction: column;
            font-family: poppins, sans-serif;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        footer {
            padding: 2rem 1rem;
            background-color: #f8f9fa;
            font-size: 0.875rem;
        }

        /* ========= LOGIN PAGE ========= */
        .login-card {
            max-width: 400px;
            margin: auto;
            margin-top: 100px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .login-card h4 {
            font-weight: 700;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #dc3545;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-danger:hover {
            background-color: #b02a37;
        }

        /* ========= HOMEPAGE MENU: ROW STYLE ========= */
        .menu-row {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 700px;
            transition: 0.3s;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 0.6s ease forwards;
        }

        .menu-row-judul {
            background-color: #919191ff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            width: 100%;
            color: #fff;
            max-width: 700px;
            transition: 0.3s;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 0.6s ease forwards;
        }

        .menu-row:hover {
            background-color: #fef4f4;
            transform: translateY(-4px);
        }

        .icon-top {
            font-size: 40px;
            color: #c0392b;
        }

        .divider-vert {
            width: 2px;
            height: 60px;
            background-color: #ddd;
            margin: 0 15px;
        }

        .menu-left {
            min-width: 100px;
        }

        .menu-right {
            flex: 1;
        }

        /* ========= ANIMASI ========= */
        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .delay-0 {
            animation-delay: 0.2s;
        }

        .delay-1 {
            animation-delay: 0.5s;
        }

        .delay-2 {
            animation-delay: 0.8s;
        }

        .container {
            flex: 1;
        }

        .title .in {
            color: orangered;
        }
    </style>
</head>

<d>
    <!-- Fullscreen Tengah -->
    <div class="flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="container text-center">
            <h4 class="mb-3"><img src="{{ asset('assets/image/pemprov.png') }}"></h4>
            <h1 class="display-1 fw-bold text-secondary title">
                SADAR<span class="in">IN</span>
            </h1>
            <p class="display-7 fw-bold text-secondary">
                Sistem Aplikasi Data dan Arsip Internal
                Dinas Kebudayaan Provinsi Bali
            </p>
            <p class="display-7 fw-bold text-secondary">
                Data Detail Pegawai
            </p>
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="container mt-5" style="text-align: left;">
                <div class="container mt-5" style="max-width: 700px;">
                    <h3 class="mb-4">Detail Pegawai</h3>

                    <div class="card shadow-sm p-4">
                        <div class="d-flex align-items-center mb-2">
                            {{-- Tombol Back di kiri --}}
                            <button onclick="window.history.back()" class="btn btn-dark me-auto">
                                ← Kembali
                            </button>
                            &nbsp;

                            <!-- <div class="d-flex gap-2">
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#pemuktahiranPegawaiModal">
                                    <i class="bi bi-pencil-square"></i>
                                    Ajukan Pemutakhiran Data
                                </button>
                            </div> -->
                        </div>
                        <div class="d-flex align-items-center justify-content-end mb-2">
                            {{-- Tombol aksi di kanan --}}
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editPegawaiModal">
                                    <i class="bi bi-pencil-square"></i> Edit Data
                                </button>

                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editPasFoto">
                                    <i class="bi bi-pencil-square"></i> Edit Pas Foto
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex align-items-center justify-content-end mb-2">
                            {{-- Tombol aksi di kanan --}}
                            <!-- <div class="d-flex gap-2">
                                <button class="btn btn-warning open-upload-modal" data-title="Data KTP *pdf"
                                    data-route="{{ route('tambah.data.ktp') }}" data-jenis="Data KTP"
                                    data-jenisfile="dataktp">
                                    <i class="bi bi-plus-lg"></i> KTP
                                </button>
                                <button class="btn btn-warning open-upload-modal" data-title="Data NPWP *pdf"
                                    data-route="{{ route('tambah.data.npwp') }}" data-jenis="Data NPWP"
                                    data-jenisfile="datanpwp">
                                    <i class="bi bi-plus-lg"></i> NPWP
                                </button>
                                <button class="btn btn-warning open-upload-modal" data-title="Data Buku Rekening *pdf"
                                    data-route="{{ route('tambah.data.rekening') }}" data-jenis="Data Buku Rekening"
                                    data-jenisfile="databukurekening">
                                    <i class="bi bi-plus-lg"></i> Buku Rekening
                                </button>
                            </div> -->
                        </div>
                        <hr>
                        <hr>
                        <div class="d-flex align-items-center justify-content-end mb-2">
                            <!-- <div class="d-flex gap-2">
                                <button class="btn btn-primary open-upload-modal"
                                    data-title="Evaluasi Kinerja Triwulan I"
                                    data-route="{{ route('tambah.evaluasi.tw1') }}"
                                    data-jenis="Evaluasi Kinerja Triwulan I" data-jenisfile="evaluasikinerjatriwulan1">
                                    <i class="bi bi-plus-lg"></i> Evaluasi Kinerja Triwulan I
                                </button>
                                <button class="btn btn-primary open-upload-modal" data-title="Umpan Balik Triwulan I"
                                    data-route="{{ route('tambah.umpanbalik.tw1') }}"
                                    data-jenis="Umpan Balik Triwulan I" data-jenisfile="umpanbaliktriwulan1">
                                    <i class="bi bi-plus-lg"></i> Rekaman Umpan Balik Triwulan I
                                </button>
                            </div> -->
                        </div>
                        <div class="d-flex align-items-center justify-content-end mb-2">
                            <!-- <div class="d-flex gap-2">
                                <button class="btn btn-primary open-upload-modal"
                                    data-title="Evaluasi Kinerja Triwulan II"
                                    data-route="{{ route('tambah.evaluasi.tw2') }}"
                                    data-jenis="Evaluasi Kinerja Triwulan II"
                                    data-jenisfile="evaluasikinerjatriwulan2">
                                    <i class="bi bi-plus-lg"></i> Evaluasi Kinerja Triwulan II
                                </button>
                                <button class="btn btn-primary open-upload-modal" data-title="Umpan Balik Triwulan II"
                                    data-route="{{ route('tambah.umpanbalik.tw2') }}"
                                    data-jenis="Umpan Balik Triwulan II" data-jenisfile="umpanbaliktriwulan2">
                                    <i class="bi bi-plus-lg"></i> Rekaman Umpan Balik Triwulan II
                                </button>
                            </div> -->
                        </div>
                        <div class='d-flex align-items-center justify-content-end mb-2'>
                            <!-- <div class="d-flex gap-2">
                                <button class="btn btn-primary open-upload-modal"
                                    data-title="Evaluasi Kinerja Triwulan III"
                                    data-route="{{ route('tambah.evaluasi.tw3') }}"
                                    data-jenis="Evaluasi Kinerja Triwulan III"
                                    data-jenisfile="evaluasikinerjatriwulan3">
                                    <i class="bi bi-plus-lg"></i> Evaluasi Kinerja Triwulan III
                                </button>
                                <button class="btn btn-primary open-upload-modal"
                                    data-title="Umpan Balik Triwulan III"
                                    data-route="{{ route('tambah.umpanbalik.tw3') }}"
                                    data-jenis="Umpan Balik Triwulan III" data-jenisfile="umpanbaliktriwulan3">
                                    <i class="bi bi-plus-lg"></i> Rekaman Umpan Balik Triwulan III
                                </button>
                            </div> -->
                        </div>
                        <div class="d-flex align-items-center justify-content-end mb-2">
                            <!-- <div class="d-flex gap-2">
                                <button class="btn btn-primary open-upload-modal" data-title="Pakta Integritas 2025"
                                    data-route="{{ route('tambah.pakta.2025') }}" data-jenis="Pakta Integritas"
                                    data-jenisfile="paktaintegritas">
                                    <i class="bi bi-plus-lg"></i> Pakta Integritas 2025
                                </button>
                                <button class="btn btn-primary open-upload-modal"
                                    data-title="Pakta Integritas 1 Desember 2025"
                                    data-route="{{ route('tambah.pakta.1desember') }}"
                                    data-jenis="Pakta Integritas 1 Desember 2025" data-jenisfile="pakta1desember">
                                    <i class="bi bi-plus-lg"></i> Pakta Integritas Desember 2025
                                </button>

                            </div> -->
                        </div>
                        <div class="d-flex align-items-center justify-content-end mb-2">
                            <!-- <div class="d-flex gap-2">
                                <button class="btn btn-primary open-upload-modal"
                                    data-title="Evaluasi Kinerja Triwulan IV"
                                    data-route="{{ route('tambah.evaluasi.tw4') }}"
                                    data-jenis="Evaluasi Kinerja Triwulan IV"
                                    data-jenisfile="evaluasikinerjatriwulan4">
                                    <i class="bi bi-plus-lg"></i> Evaluasi Kinerja Triwulan IV
                                </button>
                                <button class="btn btn-primary open-upload-modal" data-title="Umpan Balik Triwulan IV"
                                    data-route="{{ route('tambah.umpanbalik.tw4') }}"
                                    data-jenis="Umpan Balik Triwulan IV" data-jenisfile="umpanbaliktriwulan4">
                                    <i class="bi bi-plus-lg"></i> Rekaman Umpan Balik Triwulan IV
                                </button>
                            </div> -->
                        </div>
                        <div class="d-flex align-items-center justify-content-end mb-2">
                            <!-- <div class="d-flex gap-2">
                                <button class="btn btn-primary open-upload-modal"
                                    data-title="Evaluasi Kinerja Tahunan 2025"
                                    data-route="{{ route('tambah.evaluasi.tahunan') }}"
                                    data-jenis="Evaluasi Kinerja Tahunan 2025"
                                    data-jenisfile="evaluasikinerjatahunan2025">
                                    <i class="bi bi-plus-lg"></i> Evaluasi Kinerja Tahunan 2025
                                </button>
                                <button class="btn btn-primary open-upload-modal" data-title="SKP 2025"
                                    data-route="{{ route('tambah.skp.2025') }}" data-jenis="SKP 2025"
                                    data-jenisfile="skp2025">
                                    <i class="bi bi-plus-lg"></i> SKP 2025
                                </button>
                            </div> -->
                        </div>
                        <div class="d-flex align-items-center justify-content-end mb-2">
                            <div class="d-flex gap-2">
                                <!-- <button class="btn btn-primary open-upload-modal" data-title="Model C 2025"
                                    data-route="{{ route('tambah.modelc2025') }}" data-jenis="Model C 2025"
                                    data-jenisfile="modelc2025">
                                    <i class="bi bi-plus-lg"></i> Model C 2025
                                </button> -->
                                <button class="btn btn-primary open-upload-modal" data-title="Model C 2026"
                                    data-route="{{ route('tambah.modelc2026') }}" data-jenis="Model C 2026"
                                    data-jenisfile="modelc2026">
                                    <i class="bi bi-plus-lg"></i> Model C 2026
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center mb-4">
                            <img src="{{ $user->user_foto && $user->user_foto != '-' ? asset($user->user_foto) : asset('assets/image/pemprov.png') }}"
                                alt="Foto Pegawai"
                                style="width:120px; height:180px; object-fit:cover; border-radius:5px; border:1px solid #ccc;">
                        </div>
                        <hr>
                        <div class="mb-4 text-center fw-bold">*** Data Kepegawaian ***</div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">NIP</div>
                            <div class="col-sm-8">{{ $user->user_nip }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Golongan</div>
                            <div class="col-sm-8">
                                {{ $user->golongan_nama }} ({{ $user->golongan_pangkat }})
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Kelas Jabatan</div>
                            <div class="col-sm-8">Kelas Jabatan {{ $user->user_kelasjabatan }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Bidang</div>
                            <div class="col-sm-8">{{ $user->bidang_nama }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Eselon</div>
                            <div class="col-sm-8">{{ $user->eselon_nama }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Jabatan</div>
                            <div class="col-sm-8">{{ $user->jabatan_nama }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">TMT</div>
                            <div class="col-sm-8">
                                {{ \Carbon\Carbon::parse($user->user_tmt)->translatedFormat('j F Y') }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">SPMT</div>
                            <div class="col-sm-8">
                                {{ \Carbon\Carbon::parse($user->user_spmt)->translatedFormat('j F Y') }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Jenis Kerja</div>
                            <div class="col-sm-8">
                                @php
                                    $jenis = [
                                        1 => 'Pegawai Negeri Sipil',
                                        2 => 'Pegawai Pemerintah dengan Perjanjian Kerja',
                                        3 => 'Pegawai Pemerintah dengan Perjanjian Kerja Paruh Waktu',
                                        4 => 'PJLP',
                                    ];
                                @endphp
                                {{ $jenis[$user->user_jeniskerja] ?? 'Tidak Diketahui' }}
                            </div>
                        </div>
                        <hr>
                        <div class="mb-4 text-center fw-bold">*** Data Pegawai ***</div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">NIK</div>
                            <div class="col-sm-8">{{ $user->user_nik }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Nama</div>
                            <div class="col-sm-8">
                                {{ $user->user_gelardepan && $user->user_gelardepan != '-' ? $user->user_gelardepan . ' ' : '' }}
                                {{ $user->user_nama }}
                                {{ $user->user_gelarbelakang && $user->user_gelarbelakang != '-' ? ', ' . $user->user_gelarbelakang : '' }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Jenis Kelamin</div>
                            <div class="col-sm-8">
                                {{ $user->user_jk == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Email</div>
                            <div class="col-sm-8">{{ $user->user_email }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Nomor Telepon</div>
                            <div class="col-sm-8">{{ $user->user_notelp }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Tempat dan Tanggal Lahir</div>
                            <div class="col-sm-8">
                                {{ $user->user_tempatlahir }},
                                {{ \Carbon\Carbon::parse($user->user_tgllahir)->translatedFormat('j F Y') }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Alamat</div>
                            <div class="col-sm-8">{{ $user->user_alamat }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Berkas</div>
                            <div class="col-sm-8">
                                <button class="btn btn-{{ cekBerkas($berkas, 'Data KTP') }} showFiles"
                                    data-jenis="Data KTP">KTP</button>
                                <button class="btn btn-{{ cekBerkas($berkas, 'Data NPWP') }} showFiles"
                                    data-jenis="Data NPWP">NPWP</button>
                                <button class="btn btn-{{ cekBerkas($berkas, 'Data Buku Rekening') }} showFiles"
                                    data-jenis="Data Buku Rekening">Buku Tab</button>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-4 text-center fw-bold">*** Data Pendidikan ***</div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Pendidikan</div>
                            <div class="col-sm-8">
                                {{ $user->pendidikan_jenjang }} - {{ $user->pendidikan_jurusan }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Gelar Depan</div>
                            <div class="col-sm-8">{{ $user->user_gelardepan }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Gelar Belakang</div>
                            <div class="col-sm-8">{{ $user->user_gelarbelakang }}</div>
                        </div>
                        <hr>
                        <div class="mb-4 text-center fw-bold">*** Data Penggajian ***</div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Jumlah Tanggunggan</div>
                            <div class="col-sm-8">
                                {{ $user->user_jmltanggungan }} Orang
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Nomor NPWP</div>
                            <div class="col-sm-8">{{ $user->user_npwp }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Nomor BPJS Kesehatan</div>
                            <div class="col-sm-8">{{ $user->user_bpjs }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Nomor Rekening</div>
                            <div class="col-sm-8">{{ $user->user_norek }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-bold">Status</div>
                            <div class="col-sm-8">
                                {{ $user->user_status == 1 ? 'Aktif' : 'Non-Aktif' }}
                            </div>
                        </div>
                        <hr>
                        <hr>

                        <div class="mb-4 text-center fw-bold">*** Berkas ***</div>

                        {{-- Evaluasi Kinerja --}}
                        <div class="row mb-2">
                            <div class="col-sm-6 fw-bold">Data Evaluasi Kinerja 2025</div>
                            <div class="col-sm-6">
                                <button
                                    class="btn btn-{{ cekBerkas($berkas, 'Evaluasi Kinerja Triwulan I') }} showFiles"
                                    data-jenis="Evaluasi Kinerja Triwulan I">TW I</button>
                                <button
                                    class="btn btn-{{ cekBerkas($berkas, 'Evaluasi Kinerja Triwulan II') }} showFiles"
                                    data-jenis="Evaluasi Kinerja Triwulan II">TW II</button>
                                <button
                                    class="btn btn-{{ cekBerkas($berkas, 'Evaluasi Kinerja Triwulan III') }} showFiles"
                                    data-jenis="Evaluasi Kinerja Triwulan III">TW III</button>
                                <button
                                    class="btn btn-{{ cekBerkas($berkas, 'Evaluasi Kinerja Triwulan IV') }} showFiles"
                                    data-jenis="Evaluasi Kinerja Triwulan IV">TW IV</button>
                            </div>
                        </div>

                        {{-- Rekaman Umpan Balik --}}
                        <div class="row mb-2">
                            <div class="col-sm-6 fw-bold">Data Rekaman Umpan Balik 2025</div>
                            <div class="col-sm-6">
                                <button class="btn btn-{{ cekBerkas($berkas, 'Umpan Balik Triwulan I') }} showFiles"
                                    data-jenis="Umpan Balik Triwulan I">TW I</button>
                                <button class="btn btn-{{ cekBerkas($berkas, 'Umpan Balik Triwulan II') }} showFiles"
                                    data-jenis="Umpan Balik Triwulan II">TW II</button>
                                <button class="btn btn-{{ cekBerkas($berkas, 'Umpan Balik Triwulan III') }} showFiles"
                                    data-jenis="Umpan Balik Triwulan III">TW III</button>
                                <button class="btn btn-{{ cekBerkas($berkas, 'Umpan Balik Triwulan IV') }} showFiles"
                                    data-jenis="Umpan Balik Triwulan IV">TW IV</button>
                            </div>
                        </div>
                        {{-- Penilaian Kinerja --}}
                        <div class="row mb-2">
                            <div class="col-sm-6 fw-bold">Evaluasi Kinerja Tahunan</div>
                            <div class="col-sm-6">
                                <button
                                    class="btn btn-{{ cekBerkas($berkas, 'Evaluasi Kinerja Tahunan 2025') }} showFiles"
                                    data-jenis="Evaluasi Kinerja Tahunan 2025">2025</button>
                            </div>
                        </div>
                        {{-- Pakta Integritas --}}
                        <div class="row mb-2">
                            <div class="col-sm-6 fw-bold">Data Pakta Integritas</div>
                            <div class="col-sm-6">
                                <button class="btn btn-{{ cekBerkas($berkas, 'Pakta Integritas') }} showFiles"
                                    data-jenis="Pakta Integritas">2025</button>
                                <button
                                    class="btn btn-{{ cekBerkas($berkas, 'Pakta Integritas 1 Desember 2025') }} showFiles"
                                    data-jenis="Pakta Integritas 1 Desember 2025">1 Desember 2025</button>
                            </div>

                        </div>

                        {{-- Model C --}}
                        <div class="row mb-2">
                            <div class="col-sm-6 fw-bold">Data Model C</div>
                            <div class="col-sm-6">
                                <button class="btn btn-{{ cekBerkas($berkas, 'Model C 2025') }} showFiles"
                                    data-jenis="Model C 2025">2025</button>
                                <button class="btn btn-{{ cekBerkas($berkas, 'Model C 2026') }} showFiles"
                                    data-jenis="Model C 2026">2026</button>
                            </div>
                        </div>
                        {{-- SKP --}}
                        <div class="row mb-2">
                            <div class="col-sm-6 fw-bold">Data SKP</div>
                            <div class="col-sm-6">
                                <button class="btn btn-{{ cekBerkas($berkas, 'SKP 2025') }} showFiles"
                                    data-jenis="SKP 2025">2025</button>
                            </div>
                        </div>
                    </div>
                </div>

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

    <!-- /// Modal Edit Data Pegawai -->
    <div class="modal fade" id="editPegawaiModal" tabindex="-1" aria-labelledby="editPegawaiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('pemuktahiran.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPegawaiModalLabel">Edit Data Pegawai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <!-- Gelar Depan & Belakang -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Gelar Depan (jika tidak isikan "-")</label>
                                <input type="text" name="user_gelardepan" class="form-control"
                                    value="{{ $user->user_gelardepan }}">
                            </div>
                            <div class="col">
                                <label class="form-label">Gelar Belakang (jika tidak isikan "-")</label>
                                <input type="text" name="user_gelarbelakang" class="form-control"
                                    value="{{ $user->user_gelarbelakang }}">
                            </div>
                        </div>

                        <!-- Nama -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Nama (Huruf Kapital)</label>
                                <input type="text" name="user_nama" class="form-control"
                                    value="{{ $user->user_nama }}">
                            </div>
                            <div class="col">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="user_jk" class="form-select">
                                    <option value="{{ $user->user_jk }}" selected>
                                        {{ $user->user_jk == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <!-- NIP & NIK -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">NIP (TANPA SPASI)</label>
                                <input type="text" name="user_nip" class="form-control"
                                    value="{{ $user->user_nip }}">
                            </div>
                            <div class="col">
                                <label class="form-label">NIK</label>
                                <input type="text" name="user_nik" class="form-control"
                                    value="{{ $user->user_nik }}">
                            </div>
                        </div>

                        <!-- Jabatan -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Jabatan</label>
                                <select name="jabatan_id" class="form-select">
                                    <option value="{{ $user->jabatan->jabatan_id }}" selected>
                                        {{ $user->jabatan->jabatan_nama }}
                                    </option>
                                    @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan->jabatan_id }}"
                                            {{ $user->jabatan_id == $jabatan->jabatan_id ? 'selected' : '' }}>
                                            {{ $jabatan->jabatan_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label">Bidang</label>
                                <select name="bidang_id" class="form-select">
                                    <option value="{{ $user->bidang->bidang_id }}" selected>
                                        {{ $user->bidang->bidang_nama }}
                                    </option>
                                    @foreach ($bidangs as $bidang)
                                        <option value="{{ $bidang->bidang_id }}"
                                            {{ $user->bidang_id == $bidang->bidang_id ? 'selected' : '' }}>
                                            {{ $bidang->bidang_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Pendidikan -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Jenjang Pendidikan</label>
                                <select name="user_pendidikan" class="form-select">
                                    <option value="{{ $user->user_pendidikan }}">
                                        {{ $user->pendidikan_jenjang }} - {{ $user->pendidikan_jurusan }}
                                    </option>
                                    @foreach ($pendidikans as $pendidikan)
                                        <option value="{{ $pendidikan->pendidikan_id }}"
                                            {{ $user->user_pendidikan == $pendidikan->pendidikan_id ? 'selected' : '' }}>
                                            {{ $pendidikan->pendidikan_jenjang }} -
                                            {{ $pendidikan->pendidikan_jurusan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <!-- Golongan, Kelas Jabatan, Eselon -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Golongan</label>
                                <select name="user_golongan" class="form-select">
                                    <option value="{{ $user->user_golongan }}">
                                        {{ $user->golongan_nama }}-{{ $user->golongan_pangkat }}
                                    </option>
                                    @foreach ($golongans as $golongan)
                                        <option value="{{ $golongan->golongan_id }}"
                                            {{ $user->user_golongan == $golongan->golongan_id ? 'selected' : '' }}>
                                            {{ $golongan->golongan_nama }} - {{ $golongan->golongan_pangkat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label">Kelas Jabatan</label>
                                <input type="text" name="user_kelasjabatan" class="form-control"
                                    value="{{ $user->user_kelasjabatan }}">
                            </div>
                            <div class="col">
                                <label class="form-label">Eselon</label>
                                <select name="user_eselon" class="form-select">
                                    <option value="{{ $user->user_eselon }}">{{ $user->eselon_nama }}</option>
                                    @foreach ($eselons as $eselon)
                                        <option value="{{ $eselon->eselon_id }}"
                                            {{ $user->user_eselon == $eselon->eselon_id ? 'selected' : '' }}>
                                            {{ $eselon->eselon_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Email & Telepon -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Email</label>
                                <input type="email" name="user_email" class="form-control"
                                    value="{{ $user->user_email }}">
                            </div>
                            <div class="col">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="text" name="user_notelp" class="form-control"
                                    value="{{ $user->user_notelp }}">
                            </div>
                            <div class="col">
                                <label class="form-label">Nomor Rekening</label>
                                <input type="text" name="user_norek" class="form-control"
                                    value="{{ $user->user_norek }}">
                            </div>
                        </div>

                        <!-- Data Rekening & NPWP & BPJS -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Jumlah Tanggungan</label>
                                <input type="number" name="user_jmltanggungan" class="form-control"
                                    value="{{ $user->user_jmltanggungan }}"> Orang
                            </div>
                            <div class="col">
                                <label class="form-label">Nomor NPWP</label>
                                <input type="text" name="user_npwp" class="form-control"
                                    value="{{ $user->user_npwp }}">
                            </div>
                            <div class="col">
                                <label class="form-label">Nomor BPJS Kesehatan</label>
                                <input type="text" name="user_bpjs" class="form-control"
                                    value="{{ $user->user_bpjs }}">
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="user_alamat" class="form-control">{{ $user->user_alamat }}</textarea>
                        </div>


                        <!-- SPMT & TMT -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">TMT</label>
                                <input type="date" name="user_tmt" class="form-control"
                                    value="{{ $user->user_tmt }}">
                            </div>
                            <div class="col">
                                <label class="form-label">SPMT</label>
                                <input type="date" name="user_spmt" class="form-control"
                                    value="{{ $user->user_spmt }}">
                            </div>
                        </div>
                        <!-- Tanggal Lahir -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" name="user_tempatlahir" class="form-control"
                                    value="{{ $user->user_tempatlahir }}">
                            </div>
                            <div class="col">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="user_tgllahir" class="form-control"
                                    value="{{ $user->user_tgllahir }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="form-label">Status Kerja</label>
                            <div class="col">
                                <select name="user_jeniskerja" class="form-select">
                                    <option value="{{ $user->user_jeniskerja }}" selected>
                                        {{ $user->user_jeniskerja == '1' ? 'Pegawai Negeri Sipil' : '' }}
                                        {{ $user->user_jeniskerja == '2' ? 'PPPK' : '' }}
                                        {{ $user->user_jeniskerja == '3' ? 'PPPK Paruh Waktu' : '' }}
                                        {{ $user->user_jeniskerja == '4' ? 'PJLP' : '' }}
                                    </option>
                                    <option value="1">Pegawai Negeri Sipil</option>
                                    <option value="2">PPPK</option>
                                    <option value="3">PPPK Paruh Waktu</option>
                                    <option value="4">PJLP</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <!-- /// Modal Edit Pas Foto Pegawai -->
    <div class="modal fade" id="editPasFoto" tabindex="-1" aria-labelledby="editPasFotoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('pemuktahiran.update.pasfoto') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPasFotoLabel">Edit Pas Foto Pegawai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Foto -->
                        <div class="mb-3">

                            <label class="form-label">Foto Pegawai</label>
                            <input type="file" name="user_foto" class="form-control">

                            @if ($user->user_foto != '-' && $user->user_foto != null)
                                <img src="{{ asset($user->user_foto) }}" alt="Foto Pegawai" class="mt-2"
                                    style="width:100px;height:150px;object-fit:cover;border:1px solid #ccc;">
                            @endif

                            {{-- Error khusus user_foto --}}
                            @error('user_foto')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                            <div class="mt-3">
                                <small class="text-danger fw-bold">* Ketentuan warna latar belakang foto (wajib
                                    baju
                                    keki) ukuran 4x6 :</small>
                                <ul class="list-unstyled mt-2">
                                    <li>
                                        <span class="badge" style="background:red;">&nbsp;&nbsp;&nbsp;</span>
                                        Pejabat Eselon II (Merah)
                                    </li>
                                    <li>
                                        <span class="badge" style="background:blue;">&nbsp;&nbsp;&nbsp;</span>
                                        Pejabat Eselon III (Biru)
                                    </li>
                                    <li>
                                        <span class="badge" style="background:green;">&nbsp;&nbsp;&nbsp;</span>
                                        Pejabat Eselon IV (Hijau)
                                    </li>
                                    <li>
                                        <span class="badge" style="background:orange;">&nbsp;&nbsp;&nbsp;</span>
                                        Pegawai Non Eselon (Oranye)
                                    </li>
                                    <li>
                                        <span class="badge" style="background:gray;">&nbsp;&nbsp;&nbsp;</span>
                                        Pegawai/Pejabat Fungsional (Abu-abu)
                                    </li>
                                    <li>
                                        <span class="badge" style="background:gray;">&nbsp;&nbsp;&nbsp;</span>
                                        PPPK Fungsional (Abu-abu)
                                    </li>
                                    <li>
                                        <span class="badge" style="background:orange;">&nbsp;&nbsp;&nbsp;</span>
                                        PPPK Lainnya (Oranye)
                                    </li>
                                    <li>
                                        <span class="badge"
                                            style="background:rgb(255, 255, 255); border: 1px solid rgb(0, 0, 0);">&nbsp;&nbsp;&nbsp;</span>
                                        PJLP (Putih)
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Footer -->

    <footer class="text-center py-4 px-3 bg-light small text-muted">
        &copy; {{ date('Y') }} Dinas Kebudayaan Provinsi Bali — <strong>SADARIN</strong>. All rights reserved.
        <span class="text-danger">|</span>
        <span class="text-dark">Crafted by <strong>ARIN</strong></span>
        <span class="text-muted">with Pranata Komputer Ahli Pertama <i
                class="bi bi-heart-fill text-danger"></i></span>
    </footer>
    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
        $(document).ready(function() {
            // Untuk Jabatan
            $('select[name="jabatan_id"]').select2({
                dropdownParent: $('#editPegawaiModal'),
                width: '100%'
            });

            // Untuk Bidang
            $('select[name="bidang_id"]').select2({
                dropdownParent: $('#editPegawaiModal'),
                width: '100%'
            });

            // Untuk Golongan
            $('select[name="user_golongan"]').select2({
                dropdownParent: $('#editPegawaiModal'),
                width: '100%'
            });

            // Untuk Eselon
            $('select[name="user_eselon"]').select2({
                dropdownParent: $('#editPegawaiModal'),
                width: '100%'
            });

            // Untuk Pendidikan
            $('select[name="user_pendidikan"]').select2({
                dropdownParent: $('#editPegawaiModal'),
                width: '100%'
            });
        });
    </script>
    <script>
        const allFiles = @json($berkas);

        document.querySelectorAll('.showFiles').forEach(btn => {
            btn.addEventListener('click', function() {
                const jenis = this.dataset.jenis;

                // cari file yang cocok dengan jenis tombol
                const filtered = allFiles.filter(f =>
                    f.kumpulan_jenis.toLowerCase().trim() === jenis.toLowerCase().trim()
                );

                if (filtered.length === 0) {
                    alert('Tidak ada file ditemukan untuk ' + jenis);
                    return;
                }

                // kalau ada lebih dari 1 file, buka semuanya di tab baru
                filtered.forEach(file => {
                    const fileUrl = file.kumpulan_file;
                    window.open(fileUrl, '_blank');
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


    </body>

</html>
