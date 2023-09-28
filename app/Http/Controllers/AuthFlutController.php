<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthFlutController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nama_guru' => 'required',
            'nip_nik' => 'required|unique:guru',
            'sekolah_id' => 'required',
            'password' => 'required',
        ]);

        $user = new Guru([
            'nama_guru' => $request->nama_guru,
            'sekolah_id' => $request->sekolah_id,
            'nip_nik' => $request->nip_nik,
            'password' => Hash::make($request->password),
        ]);

        $user->save();

        return response()->json(['message' => 'Registrasi berhasil'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
        'nip_nik' => 'required',
        'password' => 'required',
    ]);

    $user = Guru::where('nip_nik', $request->nip_nik)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'nip_nik' => ['Kredensial yang diberikan tidak valid.'],
        ]);
    }

    // Cek apakah pengguna sudah memiliki device_id yang tersimpan
    if (!$user->device_id) {
        // Jika tidak, simpan device_id yang diterima dari permintaan
        $user->device_id = $request->device_id;
        $user->save();
    } elseif ($user->device_id !== $request->device_id) {
        return response()->json(['message' => 'Login di perangkat lain dilarang'], 401);
    }

    $token = $user->createToken('yaotoken')->accessToken;
    

    return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->token()->revoke();

        return response()->json(['message' => 'Berhasil logout'], 200);
    }

       public function user(Request $request)
    {
        $user = Auth::user();

        // Mengambil informasi pengguna dengan data sekolah yang tergabung
        $userInfo = [
            'guru_id' => $user->id,
            'nama_guru' => $user->nama_guru,
            'nip_nik' => $user->nip_nik,
            'nama_sekolah' => $user->sekolah->nama,
            'sekolah_id' => $user->sekolah->id,
            
            // Informasi lain yang Anda perlukan
        ];
        
        return response()->json(['guru' => $userInfo], 200);
    }
}

