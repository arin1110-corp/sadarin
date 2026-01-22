<form method="POST" action="{{ route('kepegawaian.export.data.rekap') }}">
    @csrf
    <!-- JENIS EXPORT -->
    <div class="mb-3">
        <label class="form-label fw-bold">Jenis Export</label>
        <select name="export_mode" id="exportMode" class="form-select" required>
            <option value="">-- Pilih --</option>
            <option value="summary">Summary / Rekap</option>
            <option value="detail">Detail Pegawai</option>
        </select>
    </div>

    <!-- SUMMARY OPTION -->
    <div id="summaryBox" class="d-none border rounded p-3">

        <div class="mb-3">
            <label class="form-label fw-bold">Rekap Berdasarkan</label>
            <select name="summary_by" class="form-select">
                <option value="bidang">Bidang</option>
                <option value="jenis_kerja">Jenis Kerja</option>
            </select>
        </div>

        <div class="row">
            <div class="col-md-6">
                <label class="fw-bold">Filter Bidang (Opsional)</label>
                <select name="bidang[]" class="form-select" multiple>
                    @foreach ($listBidang as $b)
                        <option value="{{ $b->bidang_id }}">{{ $b->bidang_nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="fw-bold">Filter Jenis Kerja</label>
                <select name="jenis_kerja[]" class="form-select" multiple>
                    <option value="1">PNS</option>
                    <option value="2">PPPK</option>
                    <option value="3">PPPK Paruh Waktu</option>
                    <option value="4">PJLP</option>
                </select>
            </div>
        </div>

    </div>

    <!-- DETAIL OPTION -->
    <div id="detailBox" class="d-none alert alert-info mt-3">
        Export detail menggunakan filter data yang aktif.
    </div>

    </div>

    <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Export
        </button>

</form>
