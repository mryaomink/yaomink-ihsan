<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Guru;
use Illuminate\Http\Request;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    function register(Request $request)
    {
        try {
            $cred = new Guru();
            $cred->nama_guru = $request->nama_guru;
            $cred->nip_nik = $request->nip_nik;
            $cred->sekolah_id = $request->sekolah_id;
            $cred->password = Hash::make($request->password);
            $cred->save();
            $response = [
                'status' => 200,
                'message' => 'Guru berhasil dibuat'
            ];
            return response()->json($response);
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => $e];
        }
    }

    function login(Request $request)
    {
        // $guru = Guru::where('nip_nik', $request->nip_nik)->first();
        // if ($guru != '[]' && Hash::check($request->password, $guru->password)) {
        //     $token = $guru->createToken('Personal Access Token')->accessToken;
        //     $response = ['status' => 200, 'token' => $token, 'guru' => $guru, 'message' => 'Berhasil login'];
        //     return response()->json($response);
        // } else if ($guru == '[]') {
        //     $response = ['status' => 500, 'message' => 'user tidak ditemukan'];
        //     return response()->json($response);
        // } else {
        //     $response = ['status' => 500, 'message' => 'Salah password'];
        //     return response()->json($response);
        // }
        $request->validate([
            'nip_nik' => 'required|string',
            'password' => 'required|string',
            'device_id' => 'required|string', // Pastikan device_id tidak null
        ]);

        // Cek login
        if (Auth::attempt(['nip_nik' => $request->nip_nik, 'password' => $request->password])) {
            $guru = Auth::guru();
            $token = $guru->createToken('Personal Access Token')->accessToken;

            // Cek jumlah perangkat terhubung, jika lebih dari satu, logout semua perangkat dan hapus token
            if ($guru->devices()->count() > 1) {
                Auth::guru()->tokens->each(function ($token, $key) {
                    $token->delete();
                });
                $guru->devices()->delete();
                return response()->json(['message' => 'Anda hanya diizinkan login dari satu perangkat. Anda telah logout dari semua perangkat.'], 401);
            }

            // Tambahkan perangkat jika belum terhubung
            $device_id = $request->device_id;
            if (!$guru->devices()->where(
                'device_id',
                $device_id
            )->exists()) {
                $guru->devices()->create(['device_id' => $device_id]);
            }

            return response()->json(['status' => 200, 'token' => $token, 'guru' => $guru, 'message' => 'Berhasil login']);
        } else {
            return response()->json(['status' => 500, 'message' => 'Salah password']);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->devices()->delete();
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Berhasil logout']);
    }

    public function getUser(Request $request)
    {
        return response()->json(['guru' => $request->user()]);
    }
}
