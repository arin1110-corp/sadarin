<!-- Modal Tambah Evaluasi dan Umpan Balik -->
<div class="modal fade" id="tambahEvaluasiModal" tabindex="-1" aria-labelledby="tambahEvaluasiModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('tambah.evaluasi.tw3') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_nip" value="{{ $user->user_nip }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahEvaluasiModalLabel">Tambah Evaluasi Kinerja Triwulan III
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Evaluasi Kinerja Triwulan III</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <input type="hidden" name="kumpulan_jenis" value="Evaluasi Kinerja Triwulan III">
                    <input type="hidden" name="user_jeniskerja" value="{{ $user->user_jeniskerja }}">
                    <input type="hidden" name="jenisfile" value="evkin">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- /// Modal Tambah Umpan Balik -->
<div class="modal fade" id="tambahUmpanBalikModal" tabindex="-1" aria-labelledby="tambahUmpanBalikModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('tambah.umpanbalik.tw3') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_nip" value="{{ $user->user_nip }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahUmpanBalikModalLabel">Tambah Umpan Balik Triwulan III</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Umpan Balik Triwulan III</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <input type="hidden" name="kumpulan_jenis" value="Umpan Balik Triwulan III">
                    <input type="hidden" name="user_jeniskerja" value="{{ $user->user_jeniskerja }}">
                    <input type="hidden" name="jenisfile" value="umpanbalik">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Umpan Balik TW IV -->
<div class="modal fade" id="tambahUmpanBalikModalTW4" tabindex="-1" aria-labelledby="tambahUmpanBalikModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('tambah.umpanbalik.tw4') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_nip" value="{{ $user->user_nip }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahUmpanBalikModalLabel">Tambah Umpan Balik Triwulan IV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Umpan Balik Triwulan IV</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <input type="hidden" name="kumpulan_jenis" value="Umpan Balik Triwulan IV">
                    <input type="hidden" name="user_jeniskerja" value="{{ $user->user_jeniskerja }}">
                    <input type="hidden" name="jenisfile" value="umpanbalik">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Tambah Evaluasi TW IV -->
<div class="modal fade" id="tambahEvaluasiModalTW4" tabindex="-1" aria-labelledby="tambahEvaluasiModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('tambah.evaluasi.tw4') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_nip" value="{{ $user->user_nip }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahEvaluasiModalLabel">Tambah Evaluasi Kinerja Triwulan IV
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Evaluasi Kinerja Triwulan IV</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <input type="hidden" name="kumpulan_jenis" value="Evaluasi Kinerja Triwulan IV">
                    <input type="hidden" name="user_jeniskerja" value="{{ $user->user_jeniskerja }}">
                    <input type="hidden" name="jenisfile" value="evkin">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Evaluasi Tahunan -->
<div class="modal fade" id="tambahEvaluasiModalTahunan" tabindex="-1" aria-labelledby="tambahEvaluasiModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('tambah.evaluasi.tahunan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_nip" value="{{ $user->user_nip }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahEvaluasiModalLabel">Tambah Evaluasi Kinerja Tahunan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Evaluasi Kinerja Tahunan</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <input type="hidden" name="kumpulan_jenis" value="Evaluasi Kinerja Tahunan 2025">
                    <input type="hidden" name="user_jeniskerja" value="{{ $user->user_jeniskerja }}">
                    <input type="hidden" name="jenisfile" value="evkin2025">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /// Modal Pakta Integritas 1 Desember 2025 -->
<div class="modal fade" id="tambahPaktaIntegritas1DesemberModal" tabindex="-1"
    aria-labelledby="tambahPaktaIntegritasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('tambah.pakta.1desember') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_nip" value="{{ $user->user_nip }}">
                <input type="hidden" name="user_nik" value="{{ $user->user_nik }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahUmpanBalikModalLabel">Tambah Pakta Integritas 1 Desember
                        2025</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pakta Integritas 1 Desember 2025</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <input type="hidden" name="kumpulan_jenis" value="Pakta Integritas 1 Desember 2025">
                    <input type="hidden" name="user_jeniskerja" value="{{ $user->user_jeniskerja }}">
                    <input type="hidden" name="jenisfile" value="pakta1desember">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal SKP 2025-->
<div class="modal fade" id="tambahSkp2025Modal" tabindex="-1" aria-labelledby="tambahSkpModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('tambah.skp.2025') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_nip" value="{{ $user->user_nip }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahSkpModalLabel">Tambah SKP 2025</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">SKP 2025</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <input type="hidden" name="kumpulan_jenis" value="SKP 2025">
                    <input type="hidden" name="user_jeniskerja" value="{{ $user->user_jeniskerja }}">
                    <input type="hidden" name="jenisfile" value="skp2025">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- /// Modal Edit Data Pegawai -->
<div class="modal fade" id="editPasFoto" tabindex="-1" aria-labelledby="editPegawaiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('pemuktahiran.update.pasfoto') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPegawaiModalLabel">Edit Pas Foto Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Foto -->
                    <div class="mb-3">

                        <label class="form-label">Foto Pegawai</label>
                        <input type="file" name="user_foto" class="form-control">

                        @if ($user->user_foto != '-' && $user->user_foto != null)
                            <img src="{{ asset($user->user_foto) }}" alt="Foto Pegawai" class="mt-2"
                                style="width:100px;height:150px;object-fit:cover;border:1px solid #ccc;">
                        @endif

                        {{-- Error khusus user_foto --}}
                        @error('user_foto')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                        <div class="mt-3">
                            <small class="text-danger fw-bold">* Ketentuan warna latar belakang foto (wajib
                                baju
                                keki) ukuran 4x6 :</small>
                            <ul class="list-unstyled mt-2">
                                <li>
                                    <span class="badge" style="background:red;">&nbsp;&nbsp;&nbsp;</span>
                                    Pejabat Eselon II (Merah)
                                </li>
                                <li>
                                    <span class="badge" style="background:blue;">&nbsp;&nbsp;&nbsp;</span>
                                    Pejabat Eselon III (Biru)
                                </li>
                                <li>
                                    <span class="badge" style="background:green;">&nbsp;&nbsp;&nbsp;</span>
                                    Pejabat Eselon IV (Hijau)
                                </li>
                                <li>
                                    <span class="badge" style="background:orange;">&nbsp;&nbsp;&nbsp;</span>
                                    Pegawai Non Eselon (Oranye)
                                </li>
                                <li>
                                    <span class="badge" style="background:gray;">&nbsp;&nbsp;&nbsp;</span>
                                    Pegawai/Pejabat Fungsional (Abu-abu)
                                </li>
                                <li>
                                    <span class="badge" style="background:gray;">&nbsp;&nbsp;&nbsp;</span>
                                    PPPK Fungsional (Abu-abu)
                                </li>
                                <li>
                                    <span class="badge" style="background:orange;">&nbsp;&nbsp;&nbsp;</span>
                                    PPPK Lainnya (Oranye)
                                </li>
                                <li>
                                    <span class="badge"
                                        style="background:rgb(255, 255, 255); border: 1px solid rgb(0, 0, 0);">&nbsp;&nbsp;&nbsp;</span>
                                    Non ASN (Putih)
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pemuktahiran -->
<div class="modal fade" id="pemuktahiranPegawaiModal" tabindex="-1" aria-labelledby="pemuktahiranPegawaiModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pemuktahiranModalLabel">Pemuktahiran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pemuktahiran.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->user_id }}" />

                    <div class="modal-header">
                        <h5 class="modal-title" id="editPegawaiModalLabel">Pemuktahiran Data Pegawai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Gelar Depan & Belakang -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Gelar Depan (jika tidak isikan "-")</label>
                                <input type="text" name="user_gelardepan" class="form-control"
                                    value="{{ $user->user_gelardepan }}" />
                            </div>
                            <div class="col">
                                <label class="form-label">Gelar Belakang (jika tidak isikan "-")</label>
                                <input type="text" name="user_gelarbelakang" class="form-control"
                                    value="{{ $user->user_gelarbelakang }}" />
                            </div>
                        </div>

                        <!-- Nama & Jenis Kelamin -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Nama (Huruf Kapital)</label>
                                <input type="text" name="user_nama" class="form-control"
                                    value="{{ $user->user_nama }}" />
                            </div>
                            <div class="col">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="user_jk" class="form-select">
                                    <option value="{{ $user->user_jk }}" selected>
                                        {{ $user->user_jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <!-- NIP & NIK -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">NIP (TANPA SPASI)</label>
                                <input type="text" name="user_nip" class="form-control"
                                    value="{{ $user->user_nip }}" />
                            </div>
                            <div class="col">
                                <label class="form-label">NIK</label>
                                <input type="text" name="user_nik" class="form-control"
                                    value="{{ $user->user_nik }}" />
                            </div>
                        </div>

                        <!-- Jabatan & Bidang -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Jabatan</label>
                                <select name="jabatan_id" class="form-select">
                                    <option value="{{ $user->jabatan->jabatan_id }}" selected>
                                        {{ $user->jabatan->jabatan_nama }}</option>
                                    @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan->jabatan_id }}"
                                            {{ $user->jabatan_id == $jabatan->jabatan_id ? 'selected' : '' }}>
                                            {{ $jabatan->jabatan_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label">Bidang</label>
                                <select name="bidang_id" class="form-select">
                                    <option value="{{ $user->bidang->bidang_id }}" selected>
                                        {{ $user->bidang->bidang_nama }}</option>
                                    @foreach ($bidangs as $bidang)
                                        <option value="{{ $bidang->bidang_id }}"
                                            {{ $user->bidang_id == $bidang->bidang_id ? 'selected' : '' }}>
                                            {{ $bidang->bidang_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Pendidikan -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Jenjang Pendidikan</label>
                                <select name="user_pendidikan" class="form-select">
                                    <option value="{{ $user->user_pendidikan }}">
                                        {{ $user->pendidikan_jenjang }}
                                        - {{ $user->pendidikan_jurusan }}</option>
                                    @foreach ($pendidikans as $pendidikan)
                                        <option value="{{ $pendidikan->pendidikan_id }}"
                                            {{ $user->user_pendidikan == $pendidikan->pendidikan_id ? 'selected' : '' }}>
                                            {{ $pendidikan->pendidikan_jenjang }} -
                                            {{ $pendidikan->pendidikan_jurusan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Golongan, Kelas Jabatan, Eselon -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Golongan</label>
                                <select name="user_golongan" class="form-select">
                                    <option value="{{ $user->user_golongan }}">{{ $user->golongan_nama }} -
                                        {{ $user->golongan_pangkat }}</option>
                                    @foreach ($golongans as $golongan)
                                        <option value="{{ $golongan->golongan_id }}"
                                            {{ $user->user_golongan == $golongan->golongan_id ? 'selected' : '' }}>
                                            {{ $golongan->golongan_nama }} -
                                            {{ $golongan->golongan_pangkat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label">Kelas Jabatan</label>
                                <input type="text" name="user_kelasjabatan" class="form-control"
                                    value="{{ $user->user_kelasjabatan }}" />
                            </div>
                            <div class="col">
                                <label class="form-label">Eselon</label>
                                <select name="user_eselon" class="form-select">
                                    <option value="{{ $user->user_eselon }}">{{ $user->eselon_nama }}
                                    </option>
                                    @foreach ($eselons as $eselon)
                                        <option value="{{ $eselon->eselon_id }}"
                                            {{ $user->user_eselon == $eselon->eselon_id ? 'selected' : '' }}>
                                            {{ $eselon->eselon_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Email, Telepon, Rekening -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Email</label>
                                <input type="email" name="user_email" class="form-control"
                                    value="{{ $user->user_email }}" />
                            </div>
                            <div class="col">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="text" name="user_notelp" class="form-control"
                                    value="{{ $user->user_notelp }}" />
                            </div>
                            <div class="col">
                                <label class="form-label">Nomor Rekening</label>
                                <input type="text" name="user_norek" class="form-control"
                                    value="{{ $user->user_norek }}" />
                            </div>
                        </div>

                        <!-- NPWP, BPJS, Jumlah Tanggungan -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Jumlah Tanggungan</label>
                                <input type="number" name="user_jmltanggungan" class="form-control"
                                    value="{{ $user->user_jmltanggungan }}" /> Orang
                            </div>
                            <div class="col">
                                <label class="form-label">Nomor NPWP</label>
                                <input type="text" name="user_npwp" class="form-control"
                                    value="{{ $user->user_npwp }}" />
                            </div>
                            <div class="col">
                                <label class="form-label">Nomor BPJS Kesehatan</label>
                                <input type="text" name="user_bpjs" class="form-control"
                                    value="{{ $user->user_bpjs }}" />
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="user_alamat" class="form-control">{{ $user->user_alamat }}</textarea>
                        </div>

                        <!-- TMT & SPMT -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">TMT</label>
                                <input type="date" name="user_tmt" class="form-control"
                                    value="{{ $user->user_tmt }}" />
                            </div>
                            <div class="col">
                                <label class="form-label">SPMT</label>
                                <input type="date" name="user_spmt" class="form-control"
                                    value="{{ $user->user_spmt }}" />
                            </div>
                        </div>

                        <!-- Tempat & Tanggal Lahir -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" name="user_tempatlahir" class="form-control"
                                    value="{{ $user->user_tempatlahir }}" />
                            </div>
                            <div class="col">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="user_tgllahir" class="form-control"
                                    value="{{ $user->user_tgllahir }}" />
                            </div>
                        </div>

                        <!-- Status Kerja -->
                        <div class="row mb-3">
                            <label class="form-label">Status Kerja</label>
                            <input type="text" class="form-control"
                                value="{{ $user->user_jeniskerja == 1 ? 'PNS' : 'PPPK' }}" disabled />
                            <input type="hidden" name="user_jeniskerja" value="{{ $user->user_jeniskerja }}" />
                        </div>

                        <!-- Tombol -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- /// Modal Edit Data Pegawai -->
<div class="modal fade" id="editPegawaiModal" tabindex="-1" aria-labelledby="editPegawaiModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('pemuktahiran.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPegawaiModalLabel">Edit Data Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- Gelar Depan & Belakang -->
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Gelar Depan (jika tidak isikan "-")</label>
                            <input type="text" name="user_gelardepan" class="form-control"
                                value="{{ $user->user_gelardepan }}">
                        </div>
                        <div class="col">
                            <label class="form-label">Gelar Belakang (jika tidak isikan "-")</label>
                            <input type="text" name="user_gelarbelakang" class="form-control"
                                value="{{ $user->user_gelarbelakang }}">
                        </div>
                    </div>

                    <!-- Nama -->
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Nama (Huruf Kapital)</label>
                            <input type="text" name="user_nama" class="form-control"
                                value="{{ $user->user_nama }}">
                        </div>
                        <div class="col">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="user_jk" class="form-select">
                                <option value="{{ $user->user_jk }}" selected>
                                    {{ $user->user_jk == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <!-- NIP & NIK -->
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">NIP (TANPA SPASI)</label>
                            <input type="text" name="user_nip" class="form-control"
                                value="{{ $user->user_nip }}">
                        </div>
                        <div class="col">
                            <label class="form-label">NIK</label>
                            <input type="text" name="user_nik" class="form-control"
                                value="{{ $user->user_nik }}">
                        </div>
                    </div>

                    <!-- Jabatan -->
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Jabatan</label>
                            <select name="jabatan_id" class="form-select">
                                <option value="{{ $user->jabatan->jabatan_id }}" selected>
                                    {{ $user->jabatan->jabatan_nama }}
                                </option>
                                @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->jabatan_id }}"
                                        {{ $user->jabatan_id == $jabatan->jabatan_id ? 'selected' : '' }}>
                                        {{ $jabatan->jabatan_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Bidang</label>
                            <select name="bidang_id" class="form-select">
                                <option value="{{ $user->bidang->bidang_id }}" selected>
                                    {{ $user->bidang->bidang_nama }}
                                </option>
                                @foreach ($bidangs as $bidang)
                                    <option value="{{ $bidang->bidang_id }}"
                                        {{ $user->bidang_id == $bidang->bidang_id ? 'selected' : '' }}>
                                        {{ $bidang->bidang_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Pendidikan -->
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Jenjang Pendidikan</label>
                            <select name="user_pendidikan" class="form-select">
                                <option value="{{ $user->user_pendidikan }}">
                                    {{ $user->pendidikan_jenjang }} - {{ $user->pendidikan_jurusan }}
                                </option>
                                @foreach ($pendidikans as $pendidikan)
                                    <option value="{{ $pendidikan->pendidikan_id }}"
                                        {{ $user->user_pendidikan == $pendidikan->pendidikan_id ? 'selected' : '' }}>
                                        {{ $pendidikan->pendidikan_jenjang }} -
                                        {{ $pendidikan->pendidikan_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <!-- Golongan, Kelas Jabatan, Eselon -->
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Golongan</label>
                            <select name="user_golongan" class="form-select">
                                <option value="{{ $user->user_golongan }}">
                                    {{ $user->golongan_nama }}-{{ $user->golongan_pangkat }}
                                </option>
                                @foreach ($golongans as $golongan)
                                    <option value="{{ $golongan->golongan_id }}"
                                        {{ $user->user_golongan == $golongan->golongan_id ? 'selected' : '' }}>
                                        {{ $golongan->golongan_nama }} - {{ $golongan->golongan_pangkat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Kelas Jabatan</label>
                            <input type="text" name="user_kelasjabatan" class="form-control"
                                value="{{ $user->user_kelasjabatan }}">
                        </div>
                        <div class="col">
                            <label class="form-label">Eselon</label>
                            <select name="user_eselon" class="form-select">
                                <option value="{{ $user->user_eselon }}">{{ $user->eselon_nama }}</option>
                                @foreach ($eselons as $eselon)
                                    <option value="{{ $eselon->eselon_id }}"
                                        {{ $user->user_eselon == $eselon->eselon_id ? 'selected' : '' }}>
                                        {{ $eselon->eselon_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Email & Telepon -->
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Email</label>
                            <input type="email" name="user_email" class="form-control"
                                value="{{ $user->user_email }}">
                        </div>
                        <div class="col">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="user_notelp" class="form-control"
                                value="{{ $user->user_notelp }}">
                        </div>
                        <div class="col">
                            <label class="form-label">Nomor Rekening</label>
                            <input type="text" name="user_norek" class="form-control"
                                value="{{ $user->user_norek }}">
                        </div>
                    </div>

                    <!-- Data Rekening & NPWP & BPJS -->
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Jumlah Tanggungan</label>
                            <input type="number" name="user_jmltanggungan" class="form-control"
                                value="{{ $user->user_jmltanggungan }}"> Orang
                        </div>
                        <div class="col">
                            <label class="form-label">Nomor NPWP</label>
                            <input type="text" name="user_npwp" class="form-control"
                                value="{{ $user->user_npwp }}">
                        </div>
                        <div class="col">
                            <label class="form-label">Nomor BPJS Kesehatan</label>
                            <input type="text" name="user_bpjs" class="form-control"
                                value="{{ $user->user_bpjs }}">
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="user_alamat" class="form-control">{{ $user->user_alamat }}</textarea>
                    </div>


                    <!-- SPMT & TMT -->
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">TMT</label>
                            <input type="date" name="user_tmt" class="form-control"
                                value="{{ $user->user_tmt }}">
                        </div>
                        <div class="col">
                            <label class="form-label">SPMT</label>
                            <input type="date" name="user_spmt" class="form-control"
                                value="{{ $user->user_spmt }}">
                        </div>
                    </div>
                    <!-- Tanggal Lahir -->
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="user_tempatlahir" class="form-control"
                                value="{{ $user->user_tempatlahir }}">
                        </div>
                        <div class="col">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="user_tgllahir" class="form-control"
                                value="{{ $user->user_tgllahir }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="form-label">Status Kerja</label>
                        @php
                            $jenisKerjaMap = [
                                1 => 'PNS',
                                2 => 'PPPK',
                                3 => 'PPPK Paruh Waktu',
                                4 => 'NON ASN',
                            ];
                        @endphp
                        <input type="text" class="form-control"
                            value="{{ $jenisKerjaMap[$user->user_jeniskerja] ?? 'Tidak Diketahui' }}" disabled>
                        <input type="hidden" name="user_jeniskerja" value="{{ $user->user_jeniskerja }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
            </form>
        </div>
    </div>
</div>
<!-- /// End Modal Edit Data Pegawai -->
<!-- Modal Model C 2026  -->
<div class="modal fade" id="tambahModelC2026Modal" tabindex="-1" aria-labelledby="tambahModelC2026ModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('tambah.modelc2026') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_nip" value="{{ $user->user_nip }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahSkpModalLabel">Tambah Model C 2026</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Model C 2026</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <input type="hidden" name="kumpulan_jenis" value="Model C 2026">
                    <input type="hidden" name="user_jeniskerja" value="{{ $user->user_jeniskerja }}">
                    <input type="hidden" name="jenisfile" value="modelc2026">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
