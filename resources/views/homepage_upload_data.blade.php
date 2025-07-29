<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SADARIN - Sistem Data dan Arsip Internal</title>

    <link rel="icon" href="{{ asset('assets/image/pemprov.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.07);
            border-radius: 15px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .section-title .in {
            color: orangered;
        }

        .form-upload {
            background-color: #fcfcfc;
            border: 1px solid #ddd;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .btn-custom {
            background-color: orangered;
            border: none;
            color: #f5f5f5;
        }

        .btn-custom:hover {
            background-color: #e44d00;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="section-title">
            <img src="{{ asset('assets/image/pemprov.png') }}" width="120" class="mb-3">
            <h1>SADAR<span class="in">IN</span></h1>
            <div style="font-size: 20px; color: #666;">
                Sistem Data dan Arsip Internal<br>
                <strong>BAGIAN UMUM DAN KEPEGAWAIAN</strong>
            </div>
        </div>

        <div class="form-upload">
            <h4 class="mb-4"><i class="bi bi-cloud-upload"></i> Upload Laporan ke Google Drive</h4>
            <form method="POST" action="{{ route('data.upload') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-floating mb-3">
                    <input type="text" name="nama_bidang" class="form-control" id="nama_bidang"
                        placeholder="Nama Bidang" required>
                    <label for="nama_bidang">Nama Bidang</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" name="jenis_laporan" class="form-control" id="jenis_laporan"
                        placeholder="Jenis Laporan" required>
                    <label for="jenis_laporan">Jenis Laporan</label>
                </div>

                <div class="mb-3">
                    <label for="pdf_file" class="form-label">File PDF</label>
                    <input type="file" name="pdf_file" class="form-control" id="pdf_file" accept="application/pdf"
                        required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-custom btn-lg px-4">
                        <i class="bi bi-upload me-2"></i>Upload ke Drive
                    </button>
                </div>
            </form>
        </div>

        <footer class="mt-5">
            &copy; {{ date('Y') }} <strong>Dinas Kebudayaan Provinsi Bali</strong> —
            <span class="fw-bold">SADARIN</span><br>
            <i class="bi bi-code-slash"></i> Dibuat oleh
            <span class="text-danger mx-1"><i class="bi bi-heart-fill"></i></span>
            <span class="text-dark">Pranata Komputer Ahli Pertama</span>
        </footer>
    </div>

</body>

</html>