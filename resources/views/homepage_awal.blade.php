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
            max-width: 960px;
            margin-top: 50px;
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

        .form-access {
            margin-top: 30px;
            padding: 30px;
            background: #f1f1f1;
            border-radius: 15px;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .form-access input {
            border-radius: 10px;
            height: 45px;
        }

        .form-access button {
            border-radius: 10px;
        }

        footer {
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container text-center">
        <div class="title">
            <h5 class="mb-4">
                <img src="{{ asset('assets/image/pemprov.png') }}" width="150">
            </h5>
            <h1>SADAR<span class="in">IN</span></h1>
            <div style="font-size: 24px; color: #666;">
                Sistem Aplikasi Data dan Arsip Internal Dinas Kebudayaan Provinsi Bali
            </div>
        </div>

        <div class="form-access mt-5">
            <p class="mb-4" style="font-size: 16px; color: #555;">
                Masukkan <strong>kode akses</strong> untuk masuk ke dalam sistem data dan arsip internal:
            </p>
            <form method="POST" action="{{ route('akses.cek') }}">
                @csrf
                <div class="row justify-content-center mb-3">
                    <div class="col-md-6">
                        <input type="text" name="kode_akses" class="form-control" placeholder="Masukkan kode akses">
                    </div>
                </div>
                @if ($errors->any())
                <div style="color:red;">
                    {{ $errors->first('kode_akses') }}
                </div>
                @endif
                <button type="submit" class="btn btn-danger px-5">Masuk Sistem</button>
            </form>
        </div>

        <div class="mt-5">
            <a href="#" class="btn btn-outline-secondary me-2">Beranda</a>
            <a href="#" class="btn btn-outline-primary">Panduan Pengguna</a>
        </div>

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