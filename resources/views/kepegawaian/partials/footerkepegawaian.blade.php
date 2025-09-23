<footer class="text-center py-3 px-3 bg-light small border-top">
    <div class="d-flex flex-column flex-md-row justify-content-center align-items-center">
        <span class="me-2">&copy; {{ date('Y') }} <strong>Dinas Kebudayaan Provinsi Bali</strong></span>
        <span class="text-danger d-none d-md-inline mx-2">|</span>
        <span class="me-2">Sistem <strong>SADARIN</strong></span>
        <span class="text-danger d-none d-md-inline mx-2">|</span>
        <span>
            Crafted by <strong>ARIN</strong> —
            <span class="text-muted">Pranata Komputer Ahli Pertama</span>
            <i class="bi bi-heart-fill text-danger ms-1"></i>
        </span>
    </div>
</footer>
<script>
    $(document).ready(function() {
        $('#tableAll, #tablePns, #tablePppk').DataTable({
            responsive: true,
            autoWidth: false,
            lengthChange: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "→",
                    previous: "←"
                }
            },
            // FIX supaya sidebar tidak keganti style
            drawCallback: function() {
                $('.sidebar').css('background', '#212529');
            }
        });
    });
</script>