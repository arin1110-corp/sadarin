<nav class="col-md-2 d-none d-md-block sidebar p-3">
    <h4 class="mb-3"><img src="{{asset('assets/image/pemprov.png')}}" width="100"></h4>
    <h4>SADAR<in>IN</in>
    </h4>
    <h4>Admin</h4>
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
            <a class="nav-link" href="{{route('dashboard')}}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link" href="{{route('admin.bidang')}}"><i class="bi bi-card-text"></i> Bidang</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link" href="{{route('admin.subbag')}}"><i class="bi bi-card-heading"></i> Sub Bagian</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link" href="{{route('admin.navigasi')}}"><i class="bi bi-chat-square-quote"></i> Navigasi</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link" href="{{route('admin.subnavigasi')}}"><i class="bi bi-collection-fill"></i> Sub
                Navigasi</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link" href="{{route('admin.struktur')}}"><i class="bi bi-diagram-3"></i> Struktur
                Pegawai</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link" href="{{route('admin.user')}}"><i class="bi bi-people-fill"></i> User</a>
        </li>
    </ul>
</nav>