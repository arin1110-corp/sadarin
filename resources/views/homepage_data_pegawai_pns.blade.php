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

        .card-custom-bidang:hover {
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

        .card-custom-golongan {
            background-color: #75050aff;
            /* Orange gelap */
            color: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(252, 57, 57, 0.15);
            transition: transform 0.2s ease-in-out;
        }

        .card-custom-golongan:hover {
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

        <!-- Statistik Cards -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-custom p-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-people-fill card-icon me-3"></i>
                        <div>
                            <h5>Total Pegawai</h5>
                            <p>{{ $totalPegawai }} Orang</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom p-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-gender-male card-icon me-3"></i>
                        <div>
                            <h5>Laki-laki</h5>
                            <p>{{ $jumlahLaki }} Orang</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom p-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-gender-female card-icon me-3"></i>
                        <div>
                            <h5>Perempuan</h5>
                            <p>{{ $jumlahPerempuan }} Orang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekap Bidang -->
        <div class="mt-5">
            <h4 class="mb-3 text-secondary">Rekap Pegawai per Bidang</h4>
            <div class="row g-4">
                @foreach($rekapBidang as $data)
                <div class="col-md-4">
                    <div class="card card-custom-bidang p-3">
                        <h6>{{ $data['nama'] }}</h6>
                        <p>{{ $data['jumlah'] }} Orang</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <!-- Rekap Jabatan -->
        <div class="mt-5">
            <h4 class="mb-3 text-secondary">Rekap Pegawai per Jabatan</h4>
            <div class="row g-4">
                @foreach($rekapJabatan as $data)
                <div class="col-md-4">
                    <div class="card card-custom-jabatan p-3">
                        <h6>{{ $data['nama'] }}</h6>
                        <p>{{ $data['jumlah'] }} Orang</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <!-- Rekap Jabatan -->
        <div class="mt-5">
            <h4 class="mb-3 text-secondary">Rekap Pegawai per Jabatan</h4>
            <div class="row g-4">
                @foreach($rekapGolongan as $data)
                <div class="col-md-4">
                    <div class="card card-custom-golongan p-3">
                        <h6>{{ $data['nama'] }}</h6>
                        <p>{{ $data['jumlah'] }} Orang</p>
                    </div>
                </div>
                @endforeach
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
                    pageLength: 5,
                    lengthMenu: [5, 10, 25, 50],
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        paginate: {
                            previous: "Sebelumnya",
                            next: "Berikutnya"
                        }
                    }
                });
            });
        </script>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>