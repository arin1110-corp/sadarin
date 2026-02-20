<nav class="col-md-2 d-none d-md-block sidebar p-3 bg-dark text-white" style="height:100vh; overflow-y:auto;">
    <div class="text-center mb-4">
        <img src="{{ asset('assets/image/pemprov.png') }}" alt="Logo" width="80" class="mb-2">
        <h5 class="fw-bold">SADAR<in>IN</in></h5>
        <span class="small text-white">Kepegawaian Admin Panel</span>
    </div>
    <hr class="text-secondary">

    @php
        $menuItems = [
            ['route' => 'kepegawaian.dashboard', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard'],
            ['route' => 'kepegawaian.data.pegawai', 'icon' => 'bi-people', 'label' => 'Data Pegawai'],
            ['route' => 'kepegawaian.data.berkala', 'icon' => 'bi-person-lines-fill', 'label' => 'Data Kenaikan Gaji Berkala'],
            ['route' => 'kepegawaian.data.naikpangkat', 'icon' => 'bi-person-fill-up', 'label' => 'Data Kenaikan Pangkat'],
            ['route' => 'kepegawaian.data.pensiun', 'icon' => 'bi-person-fill-slash', 'label' => 'Data Pensiun'],
            ['route' => 'kepegawaian.data.pegawai.pemuktahiran', 'icon' => 'bi-person-plus', 'label' => 'Pemuktahiran Data'],
            ['route' => 'kepegawaian.data.golongan', 'icon' => 'bi-diagram-3', 'label' => 'Data Golongan'],
            ['route' => 'kepegawaian.data.jabatan', 'icon' => 'bi-briefcase', 'label' => 'Data Jabatan'],
            ['route' => 'kepegawaian.data.eselon', 'icon' => 'bi-layers', 'label' => 'Data Eselon'],
            ['route' => 'kepegawaian.data.pendidikan', 'icon' => 'bi-mortarboard', 'label' => 'Data Pendidikan'],
        ];

        $currentRoute = Route::currentRouteName();
    @endphp

    <ul class="nav flex-column">
        {{-- Menu utama --}}
        @foreach ($menuItems as $item)
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center px-2 py-2 rounded {{ $currentRoute === $item['route'] ? 'active' : '' }}"
                    href="{{ isset($item['param']) ? route($item['route'], ['id' => $item['param']]) : route($item['route']) }}">
                    <i class="bi {{ $item['icon'] }} me-2"></i> {{ $item['label'] }}
                </a>
            </li>
        @endforeach

        {{-- Dropdown Data Diri--}}
        <li class="nav-item mb-1">
            <a class="nav-link d-flex align-items-center px-2 py-2 rounded dropdown-toggle text-white" data-bs-toggle="collapse" href="#dataDiriDropdown" role="button" aria-expanded="false" aria-controls="dataDiriDropdown">
                <i class="bi bi-person-circle me-2"></i> Data Diri Pegawai
            </a>
            <div class="collapse ps-4" id="dataDiriDropdown">
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.data.ktp', ['id' => 'Data KTP']) }}">Data KTP</a>
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.data.npwp', ['id' => 'Data NPWP']) }}">Data NPWP</a>
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.data.rekening', ['id' => 'Data Buku Rekening']) }}">Data Buku Rekening</a>
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.data.bpjs.kesehatan', ['id' => 'Data BPJS Kesehatan']) }}">Data BPJS Kesehatan</a>
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.data.kartu.keluarga', ['id' => 'Data Kartu Keluarga']) }}">Data Kartu Keluarga</a>
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.data.ijazah', ['id' => 'Data Ijazah Terakhir']) }}">Data Ijazah</a>
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.data.laporan.ikd', ['id' => 'Laporan IKD']) }}">Data Laporan IKD</a>
            </div>
        </li>

        {{-- Dropdown Pakta Integritas --}}
        <li class="nav-item mb-1">
            <a class="nav-link d-flex align-items-center px-2 py-2 rounded dropdown-toggle text-white" data-bs-toggle="collapse" href="#paktaDropdown" role="button" aria-expanded="false" aria-controls="evkinDropdown">
                <i class="bi bi-clipboard-data me-2"></i> Data Pakta Integritas
            </a>
            <div class="collapse ps-4" id="paktaDropdown">
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.pakta.integritas', ['id' => 'Pakta Integritas']) }}">Pakta Integritas 1 September 2025</a>
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.pakta.1desember', ['id' => 'Pakta Integritas 1 Desember 2025']) }}">Pakta Integritas 1 Desember 2025</a>
            </div>
        </li>

        {{-- Dropdown Laporan PJLP Januari 2025 --}}
        <li class="nav-item mb-1">
            <a class="nav-link d-flex align-items-center px-2 py-2 rounded dropdown-toggle text-white" data-bs-toggle="collapse" href="#pjlpDropdown" role="button" aria-expanded="false" aria-controls="pjlpDropdown">
                <i class="bi bi-file-earmark-text me-2"></i> Laporan Bulanan PJLP
            </a>
            <div class="collapse ps-4" id="pjlpDropdown">
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.laporan.pjlp.januari.2025', ['id' => 'Laporan Bulanan PJLP Januari 2025']) }}">Januari 2025</a>
            </div>
        </li>
        

        {{-- Dropdown Evaluasi Kinerja --}}
        <li class="nav-item mb-1">
            <a class="nav-link d-flex align-items-center px-2 py-2 rounded dropdown-toggle text-white" data-bs-toggle="collapse" href="#evkinDropdown" role="button" aria-expanded="false" aria-controls="evkinDropdown">
                <i class="bi bi-clipboard-data me-2"></i> Data Evaluasi Kinerja 2025
            </a>
            <div class="collapse ps-4" id="evkinDropdown">
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.evkin.tw1', ['id' => 'Evaluasi Kinerja Triwulan I']) }}">Triwulan I</a>
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.evkin.tw2', ['id' => 'Evaluasi Kinerja Triwulan II']) }}">Triwulan II</a>
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.evkin.tw3', ['id' => 'Evaluasi Kinerja Triwulan III']) }}">Triwulan III</a>
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.evkin.tw4', ['id' => 'Evaluasi Kinerja Triwulan IV']) }}">Triwulan IV</a>
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.evkin.tahunan.2025', ['id' => 'Evaluasi Kinerja Tahunan 2025']) }}">Tahunan</a>
            </div>
        </li>

        {{-- Dropdown Umpan Balik --}}
        <li class="nav-item mb-1">
            <a class="nav-link d-flex align-items-center px-2 py-2 rounded dropdown-toggle text-white" data-bs-toggle="collapse" href="#umpanDropdown" role="button" aria-expanded="false" aria-controls="umpanDropdown">
                <i class="bi bi-chat-dots me-2"></i> Data Umpan Balik 2025
            </a>
            <div class="collapse ps-4" id="umpanDropdown">
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.umpan.tw1', ['id' => 'Umpan Balik Triwulan I']) }}">Triwulan I</a>
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.umpan.tw2', ['id' => 'Umpan Balik Triwulan II']) }}">Triwulan II</a>
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.umpan.tw3', ['id' => 'Umpan Balik Triwulan III']) }}">Triwulan III</a>
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.umpan.tw4', ['id' => 'Umpan Balik Triwulan IV']) }}">Triwulan IV</a>
            </div>
        </li>

        {{-- Dropdown MODEL C --}}
        <li class="nav-item mb-1">
            <a class="nav-link d-flex align-items-center px-2 py-2 rounded dropdown-toggle text-white" data-bs-toggle="collapse" href="#modelcDropdown" role="button" aria-expanded="false" aria-controls="umpanDropdown">
                <i class="bi bi-file-earmark-text me-2"></i> Data Model C
            </a>
            <div class="collapse ps-4" id="modelcDropdown">
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.model.c.2025', ['id' => 'Model C 2025']) }}">2025</a>
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.model.c.2026', ['id' => 'Model C 2026']) }}">2026</a>
            </div>
        </li>
        {{-- Data SKP 2025 --}}
        <li class="nav-item mb-1">
            <a class="nav-link d-flex align-items-center px-2 py-2 rounded dropdown-toggle text-white" data-bs-toggle="collapse" href="#SKPdropdown" role="button" aria-expanded="false" aria-controls="SKPDropdown">
                <i class="bi bi-file-earmark-text me-2"></i> Data SKP
            </a>
            <div class="collapse ps-4" id="SKPdropdown">
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.skp.2025', ['id' => 'SKP 2025']) }}">2025</a>
            </div>
        </li>

        {{-- Dropwdown Data Coretax 2026 --}}
        <li class="nav-item mb-1">
            <a class="nav-link d-flex align-items-center px-2 py-2 rounded dropdown-toggle text-white" data-bs-toggle="collapse" href="#coretaxDropdown" role="button" aria-expanded="false" aria-controls="coretaxDropdown">
                <i class="bi bi-file-earmark-text me-2"></i> Data Coretax
            </a>
            <div class="collapse ps-4" id="coretaxDropdown">
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.coretax.2026', ['id' => 'Coretax 2026']) }}">2026</a>
            </div>
        </li>

        {{-- Dropdown Data Perjanjian Kinerja 2026 --}}
        <li class="nav-item mb-1">
            <a class="nav-link d-flex align-items-center px-2 py-2 rounded dropdown-toggle text-white" data-bs-toggle="collapse" href="#perjanjianKinerjaDropdown" role="button" aria-expanded="false" aria-controls="perjanjianKinerjaDropdown">
                <i class="bi bi-file-earmark-text me-2"></i> Data Perjanjian Kinerja
            </a>
            <div class="collapse ps-4" id="perjanjianKinerjaDropdown">
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.data.perjanjian.kinerja.2026', ['id' => 'Perjanjian Kinerja 2026']) }}">2026</a>
            </div>
        </li>
    </ul>
</nav>
