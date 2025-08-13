<!DOCTYPE html>
<html lang="en">

<head>
    <title>SADARIN - Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/image/pemprov.png') }}" type="image/x-icon">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Poppins', sans-serif;
        }

        .in {
            color: #ff6600;
            /* Warna oranye */
        }

        .card-custom {
            background-color: #f18943ff;
            /* Orange gelap */
            color: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(252, 57, 57, 0.15);
            transition: transform 0.2s ease-in-out;
        }

        .card-custom:hover {
            transform: translateY(-5px);
        }

        .card-custom-bidang {
            background-color: #50064cff;
            /* Orange gelap */
            color: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(252, 57, 57, 0.15);
            transition: transform 0.2s ease-in-out;
        }

        .card-custom:hover {
            transform: translateY(-5px);
        }

        .card-custom-jabatan {
            background-color: #0b2064ff;
            /* Orange gelap */
            color: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(252, 57, 57, 0.15);
            transition: transform 0.2s ease-in-out;
        }

        .card-custom-jabatan:hover {
            transform: translateY(-5px);
        }

        .card-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }

        .card-body p {
            margin: 0;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <!-- Header -->
        <div class="text-center mb-4">
            <img src="{{ asset('assets/image/pemprov.png') }}" height="80" alt="">
            <h1 class="fw-bold text-secondary mt-2">SADAR<span class="in">IN</span></h1>
            <p class="text-muted">Sistem Aplikasi Data dan Arsip Internal - Dinas Kebudayaan Provinsi Bali</p>
        </div>


        <!-- Daftar Pegawai (Collapse) -->
        <!-- Bagian Rincian Pegawai -->
        <div class="mt-5">
            <h4 class="text-secondary mb-3">Rincian Pegawai</h4>

            <!-- Tabs -->
            <ul class="nav nav-tabs" id="pegawaiTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="seluruh-tab" data-bs-toggle="tab" data-bs-target="#seluruh"
                        type="button" role="tab">Seluruh</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pns-tab" data-bs-toggle="tab" data-bs-target="#pns" type="button"
                        role="tab">PNS</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pppk-tab" data-bs-toggle="tab" data-bs-target="#pppk" type="button"
                        role="tab">PPPK</button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content p-3 bg-white shadow-sm rounded-bottom">
                <!-- Seluruh -->
                <div class="tab-pane fade show active" id="seluruh" role="tabpanel">
                    <div class="table-responsive">
                        <table id="table-seluruh" class="table table-striped table-bordered align-middle">
                            <thead class="table-warning text-center">
                                <tr>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Jabatan</th>
                                    <th>Bidang</th>
                                    <th>Golongan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataPegawai as $p)
                                <tr>
                                    <td>{{ $p->user_nip }}</td>
                                    <td>{{ $p->user_nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->user_tgllahir)->translatedFormat('j F Y') }}</td>
                                    <td>{{ $p->jabatan_nama }}</td>
                                    <td>{{ $p->bidang_nama }}</td>
                                    <td>{{ $p->golongan_nama }} / ({{ $p->golongan_pangkat }})</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PNS -->
                <div class="tab-pane fade" id="pns" role="tabpanel">
                    <div class="table-responsive">
                        <table id="table-pns" class="table table-striped table-bordered align-middle">
                            <thead class="table-warning text-center">
                                <tr>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Jabatan</th>
                                    <th>Bidang</th>
                                    <th>Golongan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataPegawai->where('user_jeniskerja', 1) as $p)
                                <tr>
                                    <td>{{ $p->user_nip }}</td>
                                    <td>{{ $p->user_nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->user_tgllahir)->translatedFormat('j F Y') }}</td>
                                    <td>{{ $p->jabatan->jabatan_nama }}</td>
                                    <td>{{ $p->bidang->bidang_nama }}</td>
                                    <td>{{ $p->golongan_nama }} / ({{ $p->golongan_pangkat }})</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PPPK -->
                <div class="tab-pane fade" id="pppk" role="tabpanel">
                    <div class="table-responsive">
                        <table id="table-pppk" class="table table-striped table-bordered align-middle">
                            <thead class="table-warning text-center">
                                <tr>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Jabatan</th>
                                    <th>Bidang</th>
                                    <th>Golongan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataPegawai->where('user_jeniskerja', 2) as $p)
                                <tr>
                                    <td>{{ $p->user_nip }}</td>
                                    <td>{{ $p->user_nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->user_tgllahir)->translatedFormat('j F Y') }}</td>
                                    <td>{{ $p->jabatan_nama }}</td>
                                    <td>{{ $p->bidang_nama }}</td>
                                    <td>{{ $p->golongan_nama }} / ({{ $p->golongan_pangkat }})</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script DataTables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#table-seluruh, #table-pns, #table-pppk').DataTable({
                    pageLength: 30,
                    lengthMenu: [30, 45, 50, 100],
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        paginate: {
                            previous: "Sebelumnya",
                            next: "Berikutnya"
                        }
                    },
                    ordering: false
                });
            });
        </script>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>