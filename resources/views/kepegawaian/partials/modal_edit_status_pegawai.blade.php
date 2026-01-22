<form action="{{ route('kepegawaian.gantistatuspegawai') }}" method="POST">
    @csrf
    <input type="hidden" name="user_id" value="{{ $user->user_id }}">
    <div class="mb-3">
        <label for="user_status" class="form-label">Status Pegawai</label>
        <select name="user_status" class="form-select">
            <option value="{{ $user->user_status }}" selected>
                {{ $user->user_status == '1' ? 'Aktif' : 'Tidak Aktif' }}
            </option>
            <option value="1">Aktif</option>
            <option value="0">Tidak Aktif</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="keterangan" class="form-label">Keterangan</label>
        <textarea name="user_ket" id="keterangan" class="form-control" rows="3" required>{{ $user->user_ket }}</textarea>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
