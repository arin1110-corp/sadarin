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
        max-width: 1300px;
        /* ukuran sedang biar pas 4 kolom */
        margin: 40px auto;
        padding: 40px;
        background: #ffffff;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 20px;
    }

    .section-title {
        text-align: center;
        margin-bottom: 40px;
    }

    .title .in {
        color: orangered;
    }

    /* Grid Container */
    .list-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        /* 4 item per baris */
        gap: 30px;
        /* jarak antar box */
        padding: 10px;
    }

    /* Responsif */
    @media (max-width: 1200px) {
        .list-container {
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }
    }

    @media (max-width: 992px) {
        .list-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }

    @media (max-width: 576px) {
        .list-container {
            grid-template-columns: 1fr;
            gap: 15px;
        }
    }

    /* Item Box */
    .list-item {
        background-color: #fefefe;
        border-left: 5px solid orangered;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .list-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }

    .list-item h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }

    .list-item p {
        color: #555;
        margin-top: 5px;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .sub-links a {
        display: inline-block;
        margin: 4px 3px;
        background-color: orangered;
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        transition: background-color 0.2s ease;
    }

    .sub-links a:hover {
        background-color: #ff7d4a;
    }

    footer {
        text-align: center;
        margin-top: 50px;
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
                <div style="font-size: 20px; color: #666;">
                    Sistem Aplikasi Data dan Arsip Internal Dinas Kebudayaan Provinsi Bali
                </div>
                <div style="font-size: 20px; color: #666;">
                    {{@$subbagNama}}
                </div>
            </div>
        </div>

        <!-- LIST -->
        <div class="list-container">
            @foreach ($datasekretariat as $item)
            <div class="list-item">
                <div>
                    <h5>{{$item->navigasisekre_nama}}</h5>
                    <p>{{$item->navigasisekre_deskripsi}}</p>
                </div>

                <div class="sub-links">
                    @foreach ($item->subnavigasisekretariat as $subitem)
                    <a href="{{$subitem->subnavigasisekre_link}}" target="_blank">
                        {{$subitem->subnavigasisekre_nama}}
                    </a>
                    @endforeach
                </div>
            </div>
            @endforeach
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