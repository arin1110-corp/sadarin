<div class="row g-3">
    <div class="col-md-4 text-center">
        <img src="{{ $user->user_foto && $user->user_foto != '-' ? asset($user->user_foto) : asset('assets/image/pemprov.png') }}"
            alt="Foto Pegawai" class="img-thumbnail rounded shadow-sm" width="384px" height="auto" loading="lazy" />
    </div>
    <div class="col-md-8">
        <table class="table table-borderless">
            <tr>
                <th width="30%" colspan="2" class="text-center">
                    *** IDENTITAS PEGAWAI
                    ***
                </th>
            </tr>
            <tr>
                <th width="30%">NIP</th>
                <td>
                    : {{ $user->user_nip }}
                </td>
            </tr>
            <tr>
                <th>NIK</th>
                <td>
                    : {{ $user->user_nik }}
                </td>
            </tr>
            <tr>
                <th>Nama</th>
                <td>
                    : {{ $user->user_nama }}
                </td>
            </tr>
            <tr>
                <th>Gelar Depan</th>
                <td>
                    :
                    {{ $user->user_gelardepan ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>Gelar Belakang</th>
                <td>
                    :
                    {{ $user->user_gelarbelakang ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td>
                    :
                    {{ $user->user_jk == 'L' ? 'Laki-laki' : ($user->user_jk == 'P' ? 'Perempuan' : '-') }}
                </td>
            </tr>
            <tr>
                <th>
                    Tempat dan Tanggal Lahir
                </th>
                <td>
                    :
                    {{ $user->user_tempatlahir }},
                    {{ \Carbon\Carbon::parse($user->user_tgllahir)->translatedFormat('j F Y') }}
                </td>
            </tr>
            <tr>
                <th>Pendidikan</th>
                <td>
                    :
                    {{ $user->pendidikan_jenjang . ' - ' . $user->pendidikan_jurusan ?? '-' }}
                </td>
            </tr>
            <tr>
                <th colspan="2" class="text-center">
                    *** IDENTITAS JABATAN
                    ***
                </th>
            </tr>
            <tr>
                <th>Jabatan</th>
                <td>
                    :
                    {{ $user->jabatan_nama ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>Golongan</th>
                <td>
                    :
                    {{ $user->golongan_nama . ' - ' . $user->golongan_pangkat ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>Eselon</th>
                <td>
                    :
                    {{ $user->eselon_nama ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>Kelas Jabatan</th>
                <td>
                    : Kelas Jabatan
                    {{ $user->user_kelasjabatan ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>Unit Kerja</th>
                <td>
                    :
                    {{ $user->bidang_nama ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>TMT</th>
                <td>
                    :
                    {{ \Carbon\Carbon::parse($user->user_tmt)->translatedFormat('j F Y') }}
                </td>
            </tr>
            <tr>
                <th>SPMT</th>
                <td>
                    :
                    {{ \Carbon\Carbon::parse($user->user_spmt)->translatedFormat('j F Y') }}
                </td>
            </tr>
            <tr>
                <th>Jenis Kerja</th>
                <td>
                    :
                    {{ $user->user_jeniskerja == '1' ? 'PNS' : ($user->user_jeniskerja == '2' ? 'PPPK' : ($user->user_jeniskerja == '3' ? 'PPPK Paruh Waktu' : 'PJLP')) }}
                </td>
            </tr>
            <!-- Informasi Kontak -->
            <tr>
                <th colspan="2" class="text-center">
                    *** INFORMASI KONTAK ***
                </th>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>
                    :
                    {{ $user->user_alamat ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>No. Telepon</th>
                <td>
                    :
                    {{ $user->user_notelp ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>Email</th>
                <td>
                    :
                    {{ $user->user_email ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>BPJS</th>
                <td>
                    :
                    {{ $user->user_bpjs ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>No. Rekening</th>
                <td>
                    :
                    {{ $user->user_norek ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>NPWP</th>
                <td>
                    :
                    {{ $user->user_npwp ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>Jumlah Tanggungan</th>
                <td>
                    :
                    {{ $user->user_jmltanggungan ?? '-' }}
                    Orang
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    : @if ($user->user_status == '1')
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Tidak Aktif</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>
