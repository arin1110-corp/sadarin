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
            <a class="nav-link d-flex align-items-center px-2 py-2 rounded dropdown-toggle text-white" data-bs-toggle="collapse" href="#umpanDropdown" role="button" aria-expanded="false" aria-controls="umpanDropdown">
                <i class="bi bi-file-earmark-text me-2"></i> Data Model C
            </a>
            <div class="collapse ps-4" id="umpanDropdown">
                <a class="nav-link text-white small py-1" href="{{ route('kepegawaian.model.c.2025', ['id' => 'Model C 2025']) }}">2025</a>
            </div>
        </li>
    </ul>
</nav>
