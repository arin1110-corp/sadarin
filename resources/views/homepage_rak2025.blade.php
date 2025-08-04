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

<body class="d-flex flex-column min-vh-100 bg-light">

    <!-- Fullscreen Tengah -->
    <div class="flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="container text-center">

            <h4 class="mb-3"><img src="{{asset('assets/image/pemprov.png')}}"></h4>
            <h1 class="display-1 fw-bold text-secondary title">
                SADAR<span class="in">IN</span>
            </h1>
            <p class="display-7 fw-bold text-secondary">
                Sistem Data dan Arsip Internal
            </p>
            <p class="display-7 fw-bold text-secondary">
                Dinas Kebudayaan Provinsi Bali
            </p>
            <p class="display-7 fw-bold text-secondary">
                Data Rencana Anggaran Kas Tahun 2025
            </p>

            <div class="container mt-5">
                <div class="d-flex flex-column gap-4 align-items-center">
                    <!-- MENU 1 -->
                    <a href="https://drive.google.com/file/d/1r5V9HCX7gBZVNiQK3ue3v-0I-aEslpTr/preview"
                        class="menu-row animate-fade delay-0 text-decoration-none text-dark">
                        <div class="d-flex align-items-center">

                            <!-- KANAN: Deskripsi -->
                            <div class="menu-right ps-3">
                                <p class="text-muted mb-0 big">2.22.02.1.02.0001 - Pelindungan, Pengembangan,
                                    Pemanfaatan
                                    Objek Pemajuan Tradisi Budaya</p>
                            </div>

                        </div>
                    </a>
                    <!-- MENU 2 -->
                    <a href="https://drive.google.com/file/d/16VZ_9vurX0e-xCk2HCPVPiaLJ-4JdnZA/preview"
                        class="menu-row animate-fade delay-0 text-decoration-none text-dark">
                        <div class="d-flex align-items-center">
                            <!-- KANAN: Deskripsi -->
                            <div class="menu-right ps-3">
                                <p class="text-muted mb-0 big">2.22.02.1.02.0002 - Pembinaan Sumber Daya Manusia,
                                    Lembaga,
                                    dan Pranata Kebudayaan</p>
                            </div>

                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <footer class="text-center py-4 px-3 bg-light small text-muted">
        &copy; {{ date('Y') }} Dinas Kebudayaan Provinsi Bali — <strong>SAPLARIN</strong>. All rights reserved.
        <span class="text-danger">|</span>
        <span class="text-dark">Crafted by <strong>ARIN</strong></span>
        <span class="text-muted">with Pranata Komputer Ahli Pertama <i class="bi bi-heart-fill text-danger"></i></span>
    </footer>
    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>