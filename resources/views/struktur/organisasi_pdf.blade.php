<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        .title {
            text-align: center;
            margin-bottom: 15px;
        }

        .box {
            border: 1px solid #333;
            border-radius: 8px;
            padding: 8px;
            text-align: center;
            margin: auto;
        }

        .foto {
            width: 80px;
            height: 110px;
            border: 1px solid #444;
            margin-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        td {
            vertical-align: top;
            text-align: center;
            padding: 8px;
        }

        hr {
            border: 0;
            border-top: 1px solid #999;
            margin: 12px 0;
        }
    </style>
</head>

<body>

    {{-- ================= JUDUL ================= --}}
    <div class="title">
        <h3>STRUKTUR ORGANISASI</h3>
        <strong>Dinas Kebudayaan Provinsi Bali</strong>
    </div>

    {{-- ================= 1. KEPALA DINAS ================= --}}
    @php
        $kadis = $dataPegawai->where('user_jabatan', 20)->first();
    @endphp

    @if ($kadis)
        <table>
            <tr>
                <td>
                    <div class="box" style="width:320px;">
                        <strong>PLT. KEPALA DINAS</strong><br>
                        <small>Dinas Kebudayaan Provinsi Bali</small><br><br>

                        @php
                            $foto =
                                $kadis->user_foto && $kadis->user_foto != '-'
                                    ? public_path($kadis->user_foto)
                                    : public_path('assets/image/pemprov.png');
                        @endphp
                        <img src="{{ $foto }}" class="foto"><br>

                        <strong>
                            {{ $kadis->user_gelardepan != '-' ? $kadis->user_gelardepan . ' ' : '' }}
                            {{ $kadis->user_nama }}
                            {{ $kadis->user_gelarbelakang != '-' ? ', ' . $kadis->user_gelarbelakang : '' }}
                        </strong><br>

                        {{ $kadis->golongan_nama }} - {{ $kadis->golongan_pangkat }}
                    </div>
                </td>
            </tr>
        </table>
    @endif

    <hr>

    {{-- ================= 2. SEKRETARIS ================= --}}
    @php
        $sekdis = $dataPegawai->where('user_jabatan', 54)->first();
    @endphp

    @if ($sekdis)
        <table>
            <tr>
                <td>
                    <div class="box" style="width:300px;">
                        <strong>SEKRETARIS</strong><br>
                        <small>Dinas Kebudayaan Provinsi Bali</small><br><br>

                        @php
                            $foto =
                                $sekdis->user_foto && $sekdis->user_foto != '-'
                                    ? public_path($sekdis->user_foto)
                                    : public_path('assets/image/pemprov.png');
                        @endphp
                        <img src="{{ $foto }}" class="foto"><br>

                        <strong>{{ $sekdis->user_nama }}</strong><br>
                        {{ $sekdis->golongan_nama }} - {{ $sekdis->golongan_pangkat }}
                    </div>
                </td>
            </tr>
        </table>
    @endif

    <hr>

    {{-- ================= 3. KEPALA BIDANG ================= --}}
    <table>
        <tr>
            @foreach ($dataPegawai->where('user_jabatan', 19) as $kabid)
                <td width="25%">
                    <div class="box">
                        <strong>{{ $kabid->jabatan_nama }}</strong><br>
                        <small>{{ $kabid->bidang_nama }}</small><br><br>

                        @php
                            $foto =
                                $kabid->user_foto && $kabid->user_foto != '-'
                                    ? public_path($kabid->user_foto)
                                    : public_path('assets/image/pemprov.png');
                        @endphp
                        <img src="{{ $foto }}" class="foto"><br>

                        <strong>{{ $kabid->user_nama }}</strong><br>
                        {{ $kabid->golongan_nama }} - {{ $kabid->golongan_pangkat }}
                    </div>
                </td>
            @endforeach
        </tr>
    </table>

    <hr>

    {{-- ================= 4. KEPALA UPTD ================= --}}
    <table>
        <tr>
            @foreach ($dataPegawai->where('user_jabatan', 29) as $kauptd)
                <td width="25%">
                    <div class="box">
                        <strong>{{ $kauptd->jabatan_nama }}</strong><br>
                        <small>{{ $kauptd->bidang_nama }}</small><br><br>

                        @php
                            $foto =
                                $kauptd->user_foto && $kauptd->user_foto != '-'
                                    ? public_path($kauptd->user_foto)
                                    : public_path('assets/image/pemprov.png');
                        @endphp
                        <img src="{{ $foto }}" class="foto"><br>

                        <strong>{{ $kauptd->user_nama }}</strong><br>
                        {{ $kauptd->golongan_nama }} - {{ $kauptd->golongan_pangkat }}
                    </div>
                </td>
            @endforeach
        </tr>
    </table>

</body>

</html>
