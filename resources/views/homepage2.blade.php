<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Menu Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Segoe UI', sans-serif;
        }

        .menu-row {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            width: 100%;
            max-width: 800px;
        }

        .menu-row:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .menu-left {
            width: 150px;
        }

        .divider-vert {
            width: 1px;
            height: 60px;
            background-color: #ccc;
        }

        .icon-top {
            font-size: 2rem;
            color: #007bff;
        }

        .big {
            font-size: 1rem;
        }

        footer {
            margin-top: 80px;
            padding: 20px 0;
            background-color: #343a40;
            color: #fff;
            text-align: center;
        }

        @media (max-width: 768px) {
            .menu-row {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .divider-vert {
                display: none;
            }

            .menu-left {
                width: 100%;
                text-align: center;
                margin-bottom: 10px;
            }

            .menu-right {
                padding-left: 0;
            }
        }
    </style>
</head>

<body>

    <div class="container mt-5">

        <div class="d-flex flex-column gap-4 align-items-center">
            <h5 class="mb-4"><img src="{{asset('asset/image/pemprov.png')}}" width="200"></h5>
            <h5 class="display-5 fw-bold text-secondary">
                SADARIN<span class="text-navy">IN</span>
            </h5>
            <p class="display-7 fw-bold text-secondary">
                Sistem Informasi Monitoring Barang Internal
            </p>
            <p class="display-7 fw-bold text-secondary">
                Dinas Kebudayaan Provinsi Bali
            </p>
            <!-- MENU 1 -->
            <a href="{{ url('/login-user') }}"
                class="menu-row d-flex align-items-center text-decoration-none text-dark">
                <div class="d-flex align-items-center" style="width: 100%;">

                    <!-- KIRI -->
                    <div class="menu-left text-center px-3">
                        <i class="bi bi-pencil-square icon-top"></i>
                        <h6 class="mt-2 fw-bold">INPUT LAPORAN</h6>
                    </div>

                    <!-- GARIS -->
                    <div class="divider-vert mx-3"></div>

                    <!-- KANAN -->
                    <div class="menu-right ps-2">
                        <p class="text-muted mb-0 big">Unggah dan Kelola Laporan Kegiatan dengan Cepat dan Aman.</p>
                    </div>
                </div>
            </a>

            <!-- MENU 2 -->
            <a href="{{ url('/login-verifikator') }}"
                class="menu-row d-flex align-items-center text-decoration-none text-dark">
                <div class="d-flex align-items-center" style="width: 100%;">

                    <div class="menu-left text-center px-3">
                        <i class="bi bi-person-check-fill icon-top"></i>
                        <h6 class="mt-2 fw-bold">VERIFIKATOR</h6>
                    </div>

                    <div class="divider-vert mx-3"></div>

                    <div class="menu-right ps-2">
                        <p class="text-muted mb-0 big">Verifikator melakukan verifikasi inputan yang sudah dilakukan.
                        </p>
                    </div>
                </div>
            </a>

            <!-- MENU 3 -->
            <a href="{{ url('/login-admin') }}"
                class="menu-row d-flex align-items-center text-decoration-none text-dark">
                <div class="d-flex align-items-center" style="width: 100%;">

                    <div class="menu-left text-center px-3">
                        <i class="bi bi-person-bounding-box icon-top"></i>
                        <h6 class="mt-2 fw-bold">ADMINISTRATOR</h6>
                    </div>

                    <div class="divider-vert mx-3"></div>

                    <div class="menu-right ps-2">
                        <p class="text-muted mb-0 big">Administrator melakukan pengelolaan data sistem.</p>
                    </div>
                </div>
            </a>

        </div>
    </div>

    <footer>
        &copy; 2025 Dinas Kebudayaan Provinsi Bali • Versi 1.0
    </footer>

</body>

</html>