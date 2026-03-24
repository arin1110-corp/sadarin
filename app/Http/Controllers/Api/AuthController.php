<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //

    public function login(Request $request)
    {
        $nip = $request->json('nip') ?? $request->input('nip');
        $password = $request->json('password') ?? $request->input('password');

        if (!$nip || !$password) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'NIP dan password wajib diisi',
                ],
                400,
            );
        }
        $user = DB::table('sadarin_user')
            ->leftJoin('sadarin_jabatan', 'sadarin_user.user_jabatan', '=', 'sadarin_jabatan.jabatan_id')
            ->leftJoin('sadarin_bidang', 'sadarin_user.user_bidang', '=', 'sadarin_bidang.bidang_id')
            ->leftJoin('sadarin_eselon', 'sadarin_user.user_eselon', '=', 'sadarin_eselon.eselon_id')
            ->leftJoin('sadarin_pendidikan', 'sadarin_user.user_pendidikan', '=', 'sadarin_pendidikan.pendidikan_id')
            ->leftJoin('sadarin_golongan', 'sadarin_user.user_golongan', '=', 'sadarin_golongan.golongan_id')
            ->leftJoin('sadarin_jeniskerja', 'sadarin_user.user_jeniskerja', '=', 'sadarin_jeniskerja.jeniskerja_id')
            ->where(function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('user_nip', '!=', '-')->where('user_nip', $request->nip);
                })->orWhere('user_nik', $request->nip);
            })
            ->select('sadarin_user.*', 'sadarin_jabatan.jabatan_nama', 'sadarin_bidang.bidang_nama', 'sadarin_eselon.eselon_nama', 'sadarin_pendidikan.pendidikan_jenjang', 'sadarin_pendidikan.pendidikan_jurusan', 'sadarin_golongan.golongan_nama', 'sadarin_golongan.golongan_pangkat', 'sadarin_jeniskerja.jeniskerja_nama')
            ->first();

        if (!$user || !Hash::check($request->password, $user->user_password)) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Login gagal',
                ],
                401,
            );
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $user->user_id,
                'nama' => $user->user_nama,
                'nip' => $user->user_nip,
                'jabatan' => $user->jabatan_nama,
                'bidang' => $user->bidang_nama,
                'eselon' => $user->eselon_nama,
                'pendidikan_jenjang' => $user->pendidikan_jenjang,
                'pendidikan_jurusan' => $user->pendidikan_jurusan,
                'golongan_nama' => $user->golongan_nama,
                'golongan_pangkat' => $user->golongan_pangkat,
                'jeniskerja' => $user->jeniskerja_nama,
            ],
        ]);
    }
}