<!DOCTYPE html>
<html lang="id">
<head>
    <title>SADARIN - Detail Pegawai</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('assets/image/pemprov.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <style>
        body {
            background: #fff7ed;
            font-family: Poppins, sans-serif;
            color: #0f172a;
        }

        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 270px;
            background: linear-gradient(180deg, #7c2d12 0%, #9a3412 45%, #c2410c 100%);
            color: #fff;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
        }

        .brand {
            padding: 24px;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }

        .brand img {
            width: 52px;
            height: 52px;
            object-fit: contain;
        }

        .brand-title {
            font-size: 26px;
            font-weight: 800;
            line-height: 1;
            letter-spacing: .5px;
        }

        .brand-title span {
            color: #fed7aa;
        }

        .brand-subtitle {
            font-size: 12px;
            color: #ffedd5;
        }

        .menu-title {
            font-size: 11px;
            color: #fed7aa;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 18px 24px 8px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #ffedd5;
            padding: 13px 18px;
            margin: 5px 14px;
            border-radius: 14px;
            cursor: pointer;
            transition: .2s;
        }

        .menu-link:hover,
        .menu-link.active {
            color: #fff;
            background: rgba(255,255,255,.16);
        }

        .logout-box {
            margin: 20px 14px;
            padding-top: 18px;
            border-top: 1px solid rgba(255,255,255,.15);
        }

        .btn-logout {
            width: 100%;
            border-radius: 14px;
            background: rgba(255,255,255,.13);
            color: #fff;
            border: 1px solid rgba(255,255,255,.22);
            padding: 12px 16px;
            text-align: left;
        }

        .btn-logout:hover {
            background: rgba(255,255,255,.22);
            color: #fff;
        }

        .main {
            margin-left: 270px;
            width: calc(100% - 270px);
        }

        .topbar {
            background: #fff7ed;
            border-bottom: 1px solid #fed7aa;
            padding: 18px 28px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .content {
            padding: 28px;
        }

        .card-soft {
            background: #fff;
            border: 1px solid #fed7aa;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(194, 65, 12, .08);
        }

        .profile-photo {
            width: 120px;
            height: 170px;
            object-fit: cover;
            border-radius: 16px;
            border: 1px solid #fed7aa;
            background: #fff7ed;
        }

        .section-title {
            font-weight: 800;
            margin-bottom: 18px;
            color: #9a3412;
        }

        .info-row {
            display: grid;
            grid-template-columns: 230px 1fr;
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px dashed #fed7aa;
        }

        .info-row:last-child {
            border-bottom: 0;
        }

        .info-label {
            font-weight: 700;
            color: #9a3412;
        }

        .btn-rounded {
            border-radius: 14px;
        }

        .berkas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: 12px;
        }

        .upload-warning-box {
            background: linear-gradient(135deg, #ffedd5, #fff7ed);
            border: 2px dashed #f97316;
            border-radius: 22px;
            padding: 16px;
            min-width: 300px;
            max-width: 500px;
            box-shadow: 0 8px 22px rgba(249, 115, 22, .15);
        }

        .upload-warning-title {
            color: #9a3412;
            font-weight: 800;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .upload-area {
            max-height: 150px;
            overflow-y: auto;
        }

        .btn-upload-alert {
            background: #f97316;
            border-color: #ea580c;
            color: #fff;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(234, 88, 12, .25);
        }

        .btn-upload-alert:hover {
            background: #c2410c;
            border-color: #c2410c;
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-primary {
            background: #ea580c;
            border-color: #ea580c;
        }

        .btn-primary:hover {
            background: #c2410c;
            border-color: #c2410c;
        }

        .btn-warning {
            background: #f97316;
            border-color: #f97316;
            color: #fff;
        }

        .btn-warning:hover {
            background: #ea580c;
            border-color: #ea580c;
            color: #fff;
        }

        .badge.bg-primary {
            background-color: #ea580c !important;
        }

        .footer {
            font-size: 13px;
            color: #9a3412;
            padding: 20px 28px;
        }

        @media(max-width: 768px) {
            .app-wrapper { display: block; }
            .sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
            }
            .main {
                margin-left: 0;
                width: 100%;
            }
            .topbar { position: relative; }
            .info-row {
                grid-template-columns: 1fr;
                gap: 4px;
            }
        }
    </style>
</head>

<body>

@php
    $jenisKerja = [
        1 => 'Pegawai Negeri Sipil',
        2 => 'Pegawai Pemerintah dengan Perjanjian Kerja',
        3 => 'Pegawai Pemerintah dengan Perjanjian Kerja Paruh Waktu',
        4 => 'PJLP',
    ];

    $namaLengkap =
        ($user->user_gelardepan && $user->user_gelardepan != '-' ? $user->user_gelardepan . ' ' : '') .
        $user->user_nama .
        ($user->user_gelarbelakang && $user->user_gelarbelakang != '-' ? ', ' . $user->user_gelarbelakang : '');

    $berkasUtama = [
        'Data KTP' => 'KTP',
        'Data NPWP' => 'NPWP',
        'Data Buku Rekening' => 'Buku Tabungan',
        'Data BPJS Kesehatan' => 'BPJS Kesehatan',
        'Data Ijazah Terakhir' => 'Ijazah',
        'Data Kartu Keluarga' => 'Kartu Keluarga',
    ];
@endphp

<div class="app-wrapper">

    <aside class="sidebar">
        <div class="brand d-flex align-items-center gap-3">
            <img src="{{ asset('assets/image/pemprov.png') }}" alt="Logo">
            <div>
                <div class="brand-title">SADAR<span>IN</span></div>
                <div class="brand-subtitle">Sistem Data dan Arsip Internal</div>
            </div>
        </div>

        <div class="menu-title">Menu Detail</div>

        <div class="menu-link active" data-target="section-ringkasan">
            <i class="bi bi-speedometer2"></i> Ringkasan
        </div>

        <div class="menu-link" data-target="section-pegawai">
            <i class="bi bi-person-badge"></i> Data Pegawai
        </div>

        <div class="menu-link" data-target="section-kepegawaian">
            <i class="bi bi-briefcase"></i> Data Kepegawaian
        </div>

        <div class="menu-link" data-target="section-pendidikan">
            <i class="bi bi-mortarboard"></i> Data Pendidikan
        </div>

        <div class="menu-link" data-target="section-penggajian">
            <i class="bi bi-cash-stack"></i> Data Penggajian
        </div>

        <div class="menu-link" data-target="section-berkas">
            <i class="bi bi-folder2-open"></i> Melihat Berkas
        </div>

        <div class="logout-box">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="main">

        <div class="topbar d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="fw-bold mb-0 text-dark">Detail Pegawai</h4>
                <small class="text-muted">SADARIN - Dinas Kebudayaan Provinsi Bali</small>
            </div>

            <div class="d-flex flex-wrap gap-2">
                

                <button class="btn btn-warning btn-rounded" data-bs-toggle="modal" data-bs-target="#editPegawaiModal">
                    <i class="bi bi-pencil-square"></i> Edit Data
                </button>

                <button class="btn btn-danger btn-rounded" data-bs-toggle="modal" data-bs-target="#editPasFoto">
                    <i class="bi bi-image"></i> Edit Foto
                </button>
            </div>
        </div>

        <div class="content">

            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card-soft p-4 mb-4">
                <div class="d-flex flex-wrap align-items-center gap-4">
                    <img class="profile-photo"
                        src="{{ $user->user_foto && $user->user_foto != '-' ? asset($user->user_foto) : asset('assets/image/pemprov.png') }}"
                        alt="Foto Pegawai">

                    <div class="flex-grow-1">
                        <h3 class="fw-bold mb-1">{{ $namaLengkap }}</h3>
                        <div class="text-muted mb-2">{{ $user->jabatan_nama }}</div>

                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-primary">{{ $user->bidang_nama }}</span>
                            <span class="badge bg-secondary">{{ $user->user_nip }}</span>
                            <span class="badge bg-{{ $user->user_status == 1 ? 'success' : 'danger' }}">
                                {{ $user->user_status == 1 ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </div>
                    </div>

                    <div class="upload-warning-box">
                        <div class="upload-warning-title">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            Perhatian, unggah berkas yang belum lengkap
                        </div>

                        <div class="upload-area d-flex flex-wrap gap-2">
                            @foreach ($tombolsFlat as $tombol)
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $expired = $tombol->tombol_expired ? \Carbon\Carbon::parse($tombol->tombol_expired) : null;
                                @endphp

                                @if (!$expired || $today->lte($expired))
                                    <button class="btn btn-upload-alert btn-sm btn-rounded open-upload-modal"
                                        data-title="{{ $tombol->tombol_nama }}"
                                        data-route="{{ route('tambah.upload.berkas', ['tombol_id' => $tombol->tombol_id]) }}"
                                        data-jenis="{{ $tombol->tombol_nama }}"
                                        data-tombol="{{ $tombol->tombol_id }}"
                                        data-jenisfile="{{ $tombol->tombol_jenisfile }}">
                                        <i class="bi bi-cloud-upload-fill"></i> {{ $tombol->tombol_nama }}
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <section id="section-ringkasan" class="content-section">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="card-soft p-4">
                            <div class="text-muted">Status</div>
                            <h4 class="fw-bold mt-2">{{ $user->user_status == 1 ? 'Aktif' : 'Non-Aktif' }}</h4>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card-soft p-4">
                            <div class="text-muted">Jenis Kerja</div>
                            <h5 class="fw-bold mt-2">{{ $jenisKerja[$user->user_jeniskerja] ?? 'Tidak Diketahui' }}</h5>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card-soft p-4">
                            <div class="text-muted">Golongan</div>
                            <h5 class="fw-bold mt-2">
                                {{ $user->golongan_nama }} {{ $user->golongan_pangkat ? '(' . $user->golongan_pangkat . ')' : '' }}
                            </h5>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card-soft p-4">
                            <div class="text-muted">Kelas Jabatan</div>
                            <h5 class="fw-bold mt-2">Kelas {{ $user->user_kelasjabatan }}</h5>
                        </div>
                    </div>
                </div>
            </section>

            <section id="section-pegawai" class="content-section d-none">
                <div class="card-soft p-4">
                    <h5 class="section-title">Data Pegawai</h5>

                    <div class="info-row"><div class="info-label">NIK</div><div>{{ $user->user_nik }}</div></div>
                    <div class="info-row"><div class="info-label">Nama</div><div>{{ $namaLengkap }}</div></div>
                    <div class="info-row"><div class="info-label">Jenis Kelamin</div><div>{{ $user->user_jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</div></div>
                    <div class="info-row"><div class="info-label">Email</div><div>{{ $user->user_email }}</div></div>
                    <div class="info-row"><div class="info-label">Nomor Telepon</div><div>{{ $user->user_notelp }}</div></div>
                    <div class="info-row">
                        <div class="info-label">Tempat dan Tanggal Lahir</div>
                        <div>{{ $user->user_tempatlahir }}, {{ \Carbon\Carbon::parse($user->user_tgllahir)->translatedFormat('j F Y') }}</div>
                    </div>
                    <div class="info-row"><div class="info-label">Alamat</div><div>{{ $user->user_alamat }}</div></div>
                </div>
            </section>

            <section id="section-kepegawaian" class="content-section d-none">
                <div class="card-soft p-4">
                    <h5 class="section-title">Data Kepegawaian</h5>

                    <div class="info-row"><div class="info-label">NIP</div><div>{{ $user->user_nip }}</div></div>
                    <div class="info-row"><div class="info-label">Golongan</div><div>{{ $user->golongan_nama }} ({{ $user->golongan_pangkat }})</div></div>
                    <div class="info-row"><div class="info-label">Kelas Jabatan</div><div>Kelas Jabatan {{ $user->user_kelasjabatan }}</div></div>
                    <div class="info-row"><div class="info-label">Bidang</div><div>{{ $user->bidang_nama }}</div></div>
                    <div class="info-row"><div class="info-label">Eselon</div><div>{{ $user->eselon_nama }}</div></div>
                    <div class="info-row"><div class="info-label">Jabatan</div><div>{{ $user->jabatan_nama }}</div></div>
                    <div class="info-row"><div class="info-label">TMT</div><div>{{ \Carbon\Carbon::parse($user->user_tmt)->translatedFormat('j F Y') }}</div></div>
                    <div class="info-row"><div class="info-label">SPMT</div><div>{{ \Carbon\Carbon::parse($user->user_spmt)->translatedFormat('j F Y') }}</div></div>
                    <div class="info-row"><div class="info-label">Lokasi Tempat Kerja</div><div>{{ $user->user_lokasikerja }}</div></div>
                    <div class="info-row"><div class="info-label">Jenis Kerja</div><div>{{ $jenisKerja[$user->user_jeniskerja] ?? 'Tidak Diketahui' }}</div></div>
                </div>
            </section>

            <section id="section-pendidikan" class="content-section d-none">
                <div class="card-soft p-4">
                    <h5 class="section-title">Data Pendidikan</h5>

                    <div class="info-row"><div class="info-label">Pendidikan</div><div>{{ $user->pendidikan_jenjang }} - {{ $user->pendidikan_jurusan }}</div></div>
                    <div class="info-row"><div class="info-label">Gelar Depan</div><div>{{ $user->user_gelardepan }}</div></div>
                    <div class="info-row"><div class="info-label">Gelar Belakang</div><div>{{ $user->user_gelarbelakang }}</div></div>
                </div>
            </section>

            <section id="section-penggajian" class="content-section d-none">
                <div class="card-soft p-4">
                    <h5 class="section-title">Data Penggajian</h5>

                    <div class="info-row"><div class="info-label">Jumlah Tanggungan</div><div>{{ $user->user_jmltanggungan }} Orang</div></div>
                    <div class="info-row"><div class="info-label">Nomor NPWP</div><div>{{ $user->user_npwp }}</div></div>
                    <div class="info-row"><div class="info-label">Nomor BPJS Kesehatan</div><div>{{ $user->user_bpjs }}</div></div>
                    <div class="info-row"><div class="info-label">Nomor Rekening</div><div>{{ $user->user_norek }}</div></div>
                    <div class="info-row"><div class="info-label">Status</div><div>{{ $user->user_status == 1 ? 'Aktif' : 'Non-Aktif' }}</div></div>
                </div>
            </section>

            <section id="section-berkas" class="content-section d-none">
                <div class="card-soft p-4">
                    <h5 class="section-title">Melihat Berkas</h5>

                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Berkas Utama</h6>

                        <div class="berkas-grid">
                            @foreach ($berkasUtama as $jenis => $label)
                                <button class="btn btn-{{ cekBerkas($berkas, $jenis) }} btn-rounded showFiles"
                                    data-jenis="{{ $jenis }}">
                                    <i class="bi bi-file-earmark-text"></i> {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Berkas Tambahan</h6>

                    @foreach ($tombolsGroup as $title => $items)
                        <div class="mb-4">
                            <div class="fw-bold text-secondary mb-2">{{ $title }}</div>

                            <div class="berkas-grid">
                                @foreach ($items as $tombol)
                                    <button class="btn btn-{{ cekBerkas($berkas, $tombol->tombol_nama) }} btn-rounded showFiles"
                                        data-jenis="{{ $tombol->tombol_nama }}">
                                        <i class="bi bi-file-earmark-check"></i> {{ $tombol->tombol_isi }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Dinas Kebudayaan Provinsi Bali — <strong>SADARIN</strong>.
            <span class="text-danger">|</span>
            Crafted by <strong>ARIN</strong>
        </div>
    </main>
</div>

{{-- MODAL UPLOAD BERKAS --}}
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
                <input type="hidden" name="tombol_id" id="tombol_id">

                <div class="modal-header bg-warning-subtle">
                    <h5 class="modal-title fw-bold text-warning-emphasis" id="modalTitle">Upload Berkas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div id="tanggalMelaporWrapper" style="display:none;" class="mb-3">
                        <label class="form-label">Tanggal Melapor Pajak</label>
                        <div class="text-danger fw-bold small mb-2">Bukan tanggal upload bukti</div>
                        <input type="date" name="tanggal_melapor" id="tanggal_melapor" class="form-control">
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        Pastikan berkas yang diunggah benar dan sesuai jenis dokumen.
                    </div>

                    <div class="mb-3">
                        <label id="labelFile" class="form-label fw-bold">File</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary btn-rounded" type="button" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT DATA PEGAWAI --}}
<div class="modal fade" id="editPegawaiModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('pemuktahiran.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="user_id" value="{{ $user->user_id }}">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Gelar Depan jika tidak ada isikan "-"</label>
                            <input type="text" name="user_gelardepan" class="form-control" value="{{ $user->user_gelardepan }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Gelar Belakang jika tidak ada isikan "-"</label>
                            <input type="text" name="user_gelarbelakang" class="form-control" value="{{ $user->user_gelarbelakang }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Huruf Kapital</label>
                            <input type="text" name="user_nama" class="form-control" value="{{ $user->user_nama }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="user_jk" class="form-select">
                                <option value="{{ $user->user_jk }}" selected>{{ $user->user_jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">NIP Tanpa Spasi</label>
                            <input type="text" name="user_nip" class="form-control" value="{{ $user->user_nip }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">NIK</label>
                            <input type="text" name="user_nik" class="form-control" value="{{ $user->user_nik }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jabatan</label>
                            <select name="jabatan_id" class="form-select">
                                @if($user->jabatan)
                                    <option value="{{ $user->jabatan->jabatan_id }}" selected>{{ $user->jabatan->jabatan_nama }}</option>
                                @endif

                                @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->jabatan_id }}" {{ $user->jabatan_id == $jabatan->jabatan_id ? 'selected' : '' }}>
                                        {{ $jabatan->jabatan_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Bidang</label>
                            <select name="bidang_id" class="form-select">
                                @if($user->bidang)
                                    <option value="{{ $user->bidang->bidang_id }}" selected>{{ $user->bidang->bidang_nama }}</option>
                                @endif

                                @foreach ($bidangs as $bidang)
                                    <option value="{{ $bidang->bidang_id }}" {{ $user->bidang_id == $bidang->bidang_id ? 'selected' : '' }}>
                                        {{ $bidang->bidang_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi Tempat Kerja</label>
                        <select name="user_lokasikerja" class="form-select">
                            <option value="{{ $user->user_lokasikerja }}" selected>{{ $user->user_lokasikerja }}</option>
                            <option value="Kantor Dinas Kebudayaan Provinsi Bali">Kantor Dinas Kebudayaan Provinsi Bali</option>
                            <option value="Kantor UPTD Museum Bali">Kantor UPTD Museum Bali</option>
                            <option value="Kantor UPTD Taman Budaya">Kantor UPTD Taman Budaya</option>
                            <option value="Kantor UPTD Monumen Perjuangan Rakyat Bali">Kantor UPTD Monumen Perjuangan Rakyat Bali</option>
                            <option value="Kota Denpasar">Kota Denpasar</option>
                            <option value="Kabupaten Badung">Kabupaten Badung</option>
                            <option value="Kabupaten Gianyar">Kabupaten Gianyar</option>
                            <option value="Kabupaten Karangasem">Kabupaten Karangasem</option>
                            <option value="Kabupaten Tabanan">Kabupaten Tabanan</option>
                            <option value="Kabupaten Bangli">Kabupaten Bangli</option>
                            <option value="Kabupaten Klungkung">Kabupaten Klungkung</option>
                            <option value="Kabupaten Buleleng">Kabupaten Buleleng</option>
                            <option value="Kabupaten Jembrana">Kabupaten Jembrana</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenjang Pendidikan</label>
                        <select name="user_pendidikan" class="form-select">
                            <option value="{{ $user->user_pendidikan }}">
                                {{ $user->pendidikan_jenjang }} - {{ $user->pendidikan_jurusan }}
                            </option>

                            @foreach ($pendidikans as $pendidikan)
                                <option value="{{ $pendidikan->pendidikan_id }}" {{ $user->user_pendidikan == $pendidikan->pendidikan_id ? 'selected' : '' }}>
                                    {{ $pendidikan->pendidikan_jenjang }} - {{ $pendidikan->pendidikan_jurusan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Golongan</label>
                            <select name="user_golongan" class="form-select">
                                <option value="{{ $user->user_golongan }}">{{ $user->golongan_nama }} - {{ $user->golongan_pangkat }}</option>

                                @foreach ($golongans as $golongan)
                                    <option value="{{ $golongan->golongan_id }}" {{ $user->user_golongan == $golongan->golongan_id ? 'selected' : '' }}>
                                        {{ $golongan->golongan_nama }} - {{ $golongan->golongan_pangkat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kelas Jabatan</label>
                            <input type="text" name="user_kelasjabatan" class="form-control" value="{{ $user->user_kelasjabatan }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Eselon</label>
                            <select name="user_eselon" class="form-select">
                                <option value="{{ $user->user_eselon }}">{{ $user->eselon_nama }}</option>

                                @foreach ($eselons as $eselon)
                                    <option value="{{ $eselon->eselon_id }}" {{ $user->user_eselon == $eselon->eselon_id ? 'selected' : '' }}>
                                        {{ $eselon->eselon_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="user_email" class="form-control" value="{{ $user->user_email }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="user_notelp" class="form-control" value="{{ $user->user_notelp }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nomor Rekening</label>
                            <input type="text" name="user_norek" class="form-control" value="{{ $user->user_norek }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Jumlah Tanggungan</label>
                            <input type="number" name="user_jmltanggungan" class="form-control" value="{{ $user->user_jmltanggungan }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nomor NPWP</label>
                            <input type="text" name="user_npwp" class="form-control" value="{{ $user->user_npwp }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nomor BPJS Kesehatan</label>
                            <input type="text" name="user_bpjs" class="form-control" value="{{ $user->user_bpjs }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="user_alamat" class="form-control" rows="3">{{ $user->user_alamat }}</textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">TMT</label>
                            <input type="date" name="user_tmt" class="form-control" value="{{ $user->user_tmt }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">SPMT</label>
                            <input type="date" name="user_spmt" class="form-control" value="{{ $user->user_spmt }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="user_tempatlahir" class="form-control" value="{{ $user->user_tempatlahir }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="user_tgllahir" class="form-control" value="{{ $user->user_tgllahir }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Kerja</label>
                        <select name="user_jeniskerja" class="form-select">
                            <option value="{{ $user->user_jeniskerja }}" selected>
                                {{ $jenisKerja[$user->user_jeniskerja] ?? 'Tidak Diketahui' }}
                            </option>
                            <option value="1">Pegawai Negeri Sipil</option>
                            <option value="2">PPPK</option>
                            <option value="3">PPPK Paruh Waktu</option>
                            <option value="4">PJLP</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-rounded">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT PAS FOTO --}}
<div class="modal fade" id="editPasFoto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('pemuktahiran.update.pasfoto') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="user_id" value="{{ $user->user_id }}">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Pas Foto Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Foto Pegawai</label>
                        <input type="file" name="user_foto" class="form-control">

                        @if ($user->user_foto != '-' && $user->user_foto != null)
                            <img src="{{ asset($user->user_foto) }}" alt="Foto Pegawai" class="mt-3"
                                style="width:100px;height:150px;object-fit:cover;border:1px solid #ccc;border-radius:10px;">
                        @endif

                        @error('user_foto')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-warning">
                        <div class="fw-bold mb-2">Ketentuan warna latar belakang foto ukuran 4x6, wajib baju keki:</div>
                        <ul class="mb-0">
                            <li>Pejabat Eselon II: Merah</li>
                            <li>Pejabat Eselon III: Biru</li>
                            <li>Pejabat Eselon IV: Hijau</li>
                            <li>Pegawai Non Eselon: Oranye</li>
                            <li>Pegawai/Pejabat Fungsional: Abu-abu</li>
                            <li>PPPK Fungsional: Abu-abu</li>
                            <li>PPPK Lainnya: Oranye</li>
                            <li>PJLP: Putih</li>
                        </ul>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-rounded">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.querySelectorAll('.menu-link').forEach(menu => {
        menu.addEventListener('click', function () {
            document.querySelectorAll('.menu-link').forEach(item => item.classList.remove('active'));
            document.querySelectorAll('.content-section').forEach(section => section.classList.add('d-none'));

            this.classList.add('active');
            document.getElementById(this.dataset.target).classList.remove('d-none');
        });
    });

    document.querySelectorAll('.open-upload-modal').forEach(btn => {
        btn.addEventListener('click', function () {
            const title = this.dataset.title;
            const route = this.dataset.route;
            const jenis = this.dataset.jenis;
            const tombolId = this.dataset.tombol;
            const jenisfile = this.dataset.jenisfile || '';

            document.getElementById('modalTitle').innerText = title;
            document.getElementById('labelFile').innerText = title;
            document.getElementById('formUploadBerkas').action = route;
            document.getElementById('kumpulan_jenis').value = jenis;
            document.getElementById('tombol_id').value = tombolId;
            document.getElementById('jenisfile').value = jenisfile;

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

            new bootstrap.Modal(document.getElementById('modalUploadBerkas')).show();
        });
    });

    const allowOpenFile = true;
    const allFiles = @json($berkas);

    document.querySelectorAll('.showFiles').forEach(btn => {
        btn.addEventListener('click', function () {
            const jenis = this.dataset.jenis;

            const filtered = allFiles.filter(file =>
                file.kumpulan_jenis.toLowerCase().trim() === jenis.toLowerCase().trim()
            );

            if (filtered.length > 0) {
                this.classList.remove('btn-secondary', 'btn-danger');
                this.classList.add('btn-success');

                if (allowOpenFile) {
                    filtered.forEach(file => {
                        window.open(file.kumpulan_file, '_blank');
                    });
                }
            } else {
                this.classList.remove('btn-secondary', 'btn-success');
                this.classList.add('btn-danger');
            }
        });
    });

    $(document).ready(function () {
        const selects = [
            'jabatan_id',
            'bidang_id',
            'user_golongan',
            'user_eselon',
            'user_pendidikan'
        ];

        selects.forEach(name => {
            $(`select[name="${name}"]`).select2({
                dropdownParent: $('#editPegawaiModal'),
                width: '100%'
            });
        });
    });
</script>

</body>
</html>