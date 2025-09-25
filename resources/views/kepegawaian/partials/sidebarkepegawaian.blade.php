<nav class="col-md-2 d-none d-md-block sidebar p-3 bg-dark text-white">
    <div class="text-center mb-4">
        <img src="{{ asset('assets/image/pemprov.png') }}" alt="Logo" width="80" class="mb-2">
        <h5 class="fw-bold">SADAR<in>IN</in>
        </h5>
        <span class="small text-white">Kepegawaian Admin Panel</span>
    </div>
    <hr class="text-secondary">

    @php
    $menuItems = [
    ['route' => 'kepegawaian.dashboard', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard'],
    ['route' => 'kepegawaian.data.pegawai', 'icon' => 'bi-people', 'label' => 'Data Pegawai'],
    ['route' => 'kepegawaian.data.naikpangkat', 'icon' => 'bi-person-fill-up', 'label' => 'Data Kenaikan Pangkat'],
    ['route' => 'kepegawaian.data.pensiun', 'icon' => 'bi-person-fill-slash', 'label' => 'Data Pensiun'],
    ['route' => 'kepegawaian.data.pegawai.pemuktahiran', 'icon' => 'bi-person-plus', 'label' => 'Pemuktahiran Data'],
    ['route' => 'kepegawaian.data.golongan', 'icon' => 'bi-diagram-3', 'label' => 'Data Golongan'],
    ['route' => 'kepegawaian.data.jabatan', 'icon' => 'bi-briefcase', 'label' => 'Data Jabatan'],
    ['route' => 'kepegawaian.data.eselon', 'icon' => 'bi-layers', 'label' => 'Data Eselon'],
    ['route' => 'kepegawaian.data.pendidikan', 'icon' => 'bi-mortarboard', 'label' => 'Data Pendidikan'],
    ['route' => 'kepegawaian.pakta.integritas', 'icon' => 'bi-card-text', 'label' => 'Pakta Integritas', 'param' =>
    'Pakta Integritas'],
    ];
    $currentRoute = Route::currentRouteName();
    @endphp

    <ul class="nav flex-column">
        @foreach ($menuItems as $item)
        <li class="nav-item mb-1">
            <a class="nav-link d-flex align-items-center px-2 py-2 rounded{{ $currentRoute === $item['route'] ? 'active' : '' }}"
                href="{{ isset($item['param']) ? route($item['route'], ['id' => $item['param']]) : route($item['route']) }}">
                <i class="bi {{ $item['icon'] }} me-2"></i> {{ $item['label'] }}
            </a>
        </li>
        @endforeach
    </ul>
</nav>