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
            background: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 40px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }

        .title .in {
            color: orangered;
        }

        /* LIST CONTAINER */

        .list-container {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* ITEM */

        .list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 25px;
            border-radius: 12px;
            border-left: 5px solid orangered;
            background: #fafafa;
            transition: 0.25s;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
        }

        .list-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        }

        /* LEFT CONTENT */

        .list-content {
            max-width: 65%;
        }

        .list-content h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .list-content p {
            margin-top: 6px;
            font-size: 14px;
            color: #555;
        }

        /* SUB LINKS */

        .sub-links {
            text-align: right;
        }

        .sub-links a {
            display: inline-block;
            margin: 4px;
            padding: 6px 12px;
            background: orangered;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            transition: 0.2s;
        }

        .sub-links a:hover {
            background: #ff7d4a;
        }

        /* RESPONSIVE */

        @media(max-width:768px) {

            .list-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .sub-links {
                text-align: left;
            }

            .list-content {
                max-width: 100%;
            }

        }

        /* FOOTER */

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

                <div style="font-size:20px;color:#666;">
                    Sistem Aplikasi Data dan Arsip Internal Dinas Kebudayaan Provinsi Bali
                </div>

                <div style="font-size:20px;color:#666;">
                    {{ @$subbagNama }}
                </div>

            </div>

        </div>

        <!-- LIST MENU -->

        <div class="list-container">

            @foreach ($datasekretariat as $item)
                <div class="list-item">

                    <div class="list-content">

                        <h5>
                            <i class="bi bi-folder2-open"></i>
                            {{ $item->navigasisekre_nama }}
                        </h5>

                        <p>
                            {{ $item->navigasisekre_deskripsi }}
                        </p>

                    </div>

                    <div class="sub-links">

                        @foreach ($item->subnavigasisekretariat as $subitem)
                            <a href="{{ $subitem->subnavigasisekre_link }}" target="_blank">
                                {{ $subitem->subnavigasisekre_nama }}
                            </a>
                        @endforeach

                    </div>

                </div>
            @endforeach

        </div>

        <footer>

            &copy; {{ date('Y') }}
            <strong>Dinas Kebudayaan Provinsi Bali</strong> —
            <span style="color:orange;font-weight:bold;">SADARIN</span>
            <br>

            <i class="bi bi-code-slash"></i>
            Dibuat oleh
            <span style="color:red"><i class="bi bi-heart-fill"></i></span>
            <span>Pranata Komputer Ahli Pertama</span>

        </footer>

    </div>

</body>

</html>
