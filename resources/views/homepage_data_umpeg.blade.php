<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SADARIN - Sistem Data dan Arsip Internal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link rel="icon" href="{{ asset('assets/image/pemprov.png') }}" type="image/x-icon">
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
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .list-item {
            background-color: #fefefe;
            border-left: 5px solid orangered;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .list-item h5 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .list-item p {
            color: #555;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        .list-item a {
            background-color: orangered;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .title .in {
            color: orangered;
        }

        .list-item a:hover {
            background-color: #ff7d4aff;
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
            <div class="title">
                <h5 class="mb-4">
                    <img src="{{ asset('assets/image/pemprov.png') }}" width="150">
                </h5>
                <h1>SADAR<span class="in">IN</span></h1>
                <div style="font-size: 24px; color: #666;">
                    Sistem Data dan Arsip Internal Dinas Kebudayaan Provinsi Bali
                </div>
                <div style="font-size: 24px; color: #666;">
                    BAGIAN UMUM DAN KEPEGAWAIAN
                </div>
            </div>
        </div>


        <!-- Menu 2 -->
        <div class="list-item flex justify-between items-center p-4 mb-4 border rounded shadow bg-white">
            <h5>Perjanjian Kinerja Pegawai</h5>
            <p>Berisi dokumen Perjanjian Kinerja Pegawai (PK) pada Dinas Kebudayaan Provinsi
                Bali.</p>
            <a href="https://drive.google.com/file/d/1PAcIwsWXYGrxUOjZWVYD11w641_TAwrn/preview"
                class="px-3 py-1 text-sm font-medium text-blue-600 border border-blue-600 rounded hover:bg-blue-600 hover:text-white transition"
                target="_blank">
                2024</a>
            <a href="https://drive.google.com/file/d/1ni0-IlRc7Jripy9-nUzLdcAZKyci4ygL/preview"
                class="px-3 py-1 text-sm font-medium text-blue-600 border border-blue-600 rounded hover:bg-blue-600 hover:text-white transition"
                target="_blank">
                2025</a>
        </div>

        <!-- Menu 3 -->
        <div class="list-item flex justify-between items-center p-4 mb-4 border rounded shadow bg-white">
            <h5>Rencana Aksi Pegawai</h5>
            <p>Berisi Rencana Aksi Pegawai pada Dinas Kebudayaan Provinsi Bali.</p>
            <a href="https://drive.google.com/file/d/1a-K6JuSvp2KMz7X6D-FpB36Ak0EoL5Yq/preview"
                class="px-3 py-1 text-sm font-medium text-blue-600 border border-blue-600 rounded hover:bg-blue-600 hover:text-white transition"
                target="_blank">
                2024-2026</a>
        </div>

        <!-- Menu 4 -->
        <div class="list-item flex justify-between items-center p-4 mb-4 border rounded shadow bg-white">
            <h5>Laporan Survey Kepuasan Masyarakat</h5>
            <p>Berisi Laporan Survey Kepuasan Masyarakat (SKM) pada Dinas Kebudayaan Provinsi Bali.</p>
            <a href="https://drive.google.com/file/d/1FV8Q511tHsNR1gdE9-7tWF41iairS38c/preview"
                class="px-3 py-1 text-sm font-medium text-blue-600 border border-blue-600 rounded hover:bg-blue-600 hover:text-white transition"
                target="_blank">
                2024-2026</a>
        </div>

        <!-- Menu 5 -->
        <div class="list-item flex justify-between items-center p-4 mb-4 border rounded shadow bg-white">
            <h5>Laporan Surat Pertanggung Jawaban Fungsional</h5>
            <p>Berisi Laporan Surat Pertanggung Jawaban (SPJ) Fungsional pada Dinas Kebudayaan Provinsi Bali.</p>
            <a href="https://drive.google.com/file/d/1Dl3ddFH-qndrEk2AsARsjigQuCt_TxU2/preview"
                class="px-3 py-1 text-sm font-medium text-blue-600 border border-blue-600 rounded hover:bg-blue-600 hover:text-white transition"
                target="_blank">
                2024</a>
            <a href="https://drive.google.com/file/d/1Dl3ddFH-qndrEk2AsARsjigQuCt_TxU2/preview"
                class="px-3 py-1 text-sm font-medium text-blue-600 border border-blue-600 rounded hover:bg-blue-600 hover:text-white transition"
                target="_blank">
                2025</a>
        </div>

        <footer class="mt-5">
            &copy; {{ date('Y') }} <strong>Dinas Kebudayaan Provinsi Bali</strong> —
            <span class="text-warning fw-bold">SADARIN</span><br>
            <i class="bi bi-code-slash"></i> Dibuat oleh
            <span class="text-danger mx-1"><i class="bi bi-heart-fill"></i></span>
            <span class="text-dark">Pranata Komputer Ahli Pertama</span>
        </footer>
    </div>

</body>

</html>