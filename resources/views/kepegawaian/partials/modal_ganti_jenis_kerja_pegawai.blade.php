<form action="{{ route('kepegawaian.gantijeniskerjapegawai') }}" method="POST">
    @csrf
    <input type="hidden" name="user_id" value="{{ $user->user_id }}">
    <div class="mb-3">
        <label for="user_jeniskerja" class="form-label">Jenis Kerja
            Pegawai</label>
        <select name="user_jeniskerja" class="form-select">
            <option value="{{ $user->user_jeniskerja }}" selected>
                {{ $user->user_jeniskerja == '1' ? 'PNS' : ($user->user_jeniskerja == '2' ? 'PPPK' : ($user->user_jeniskerja == '3' ? 'PPPK Paruh Waktu' : 'PJLP')) }}
            </option>
            <option value="1">PNS</option>
            <option value="2">PPPK</option>
            <option value="3">PPPK Paruh Waktu</option>
            <option value="4">PJLP</option>
        </select>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
