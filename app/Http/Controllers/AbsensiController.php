<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Sekolah;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    //  public function absenMasuk(Request $request)
    // {
    //     $user = $request->user(); // Mendapatkan pengguna yang sedang login
    //     $today = now()->toDateString(); // Mendapatkan tanggal hari ini

    //     // Cek apakah pengguna sudah absen masuk hari ini
    //     $absensi = Absensi::where('guru_id', $user->id)
    //         ->whereDate('tanggal', $today)
    //         ->first();

    //     if ($absensi) {
    //         return response()->json(['message' => 'Anda sudah absen masuk hari ini'], 400);
    //     }

    //     // Jika belum absen masuk hari ini, catat absen masuk
    //     $absensi = new Absensi();
    //     $absensi->guru_id = $user->id;
    //     $absensi->sekolah_id = $user->sekolah_id; // Mengambil ID sekolah dari relasi
    //     $absensi->tanggal = $today;
    //     $absensi->jam_masuk = now(); // Anda mungkin perlu menyesuaikan jam masuk sesuai dengan kebutuhan
    //     $absensi->save();

    //     return response()->json(['message' => 'Absen masuk berhasil'], 200);
    // }
   

// public function store(Request $request)
// {
//     // Validasi input
   
//         // Validasi input
//         $validatedData = $request->validate([
//             'guru_id' => 'required|integer',
//             'sekolah_id' => 'required|integer',
//             'tanggal' => 'required|date',
//             'jam_masuk' => 'required',
//             'lokasi_absensi' => 'required',
//         ]);

//         // Periksa apakah jam absensi guru sesuai dengan jam absensi sekolah
//         $sekolah = $this->getSekolah($validatedData['sekolah_id']);
//         $statusAbsensi = $this->cekStatusAbsensi(
//             $validatedData['jam_masuk'],
//             $sekolah->jam_absensi
//         );

//         // Simpan data absensi
//         $absensi = new Absensi($validatedData);
//         $absensi->status = $statusAbsensi;
//         $absensi->save();

//         return response()->json(['message' => 'Absensi berhasil'], 200);
//     }

//     private function getSekolah($sekolahId)
//     {
//         return Sekolah::findOrFail($sekolahId);
//     }

//     private function cekStatusAbsensi($jamMasuk, $jamAbsensiSekolah)
//     {
//         $jamGuruAbsensi = Carbon::parse($jamMasuk);
//         $jamAbsensiSekolah = Carbon::parse($jamAbsensiSekolah);

//         return $jamGuruAbsensi->lessThan($jamAbsensiSekolah) ? 'terlambat' : 'hadir';
//     }

//      public function absenMasuk(Request $request)
//     {
//         $guru = Guru::find($request->guru_id);
//     $sekolah = Sekolah::find($request->sekolah_id);

//         $jamMasuk = $sekolah->jam_absensi_masuk;
//         $selisih = Carbon::parse($jamMasuk)->diffInMinutes($request->jam_masuk);

//         if ($selisih > 0) {
//             $status = 'terlambat';
//         } else {
//             $status = 'masuk';
//         }

//          $absensi = new Absensi;
//     $absensi->guru()->associate($guru);
//     $absensi->sekolah()->associate($sekolah);
//     $absensi->tanggal = $request->tanggal;
//     $absensi->jam_masuk = $request->jam_masuk;
//     $absensi->lokasi = $request->lokasi;
//     $absensi->status = $status;
//     $absensi->save();

//         return response()->json([
//             'message' => 'Absen masuk berhasil',
//         ]);
//     }

// }

public function absenMasuk(Request $request)
    {
        $guru = Guru::find($request->guru_id);
    $sekolah = Sekolah::find($request->sekolah_id);

    // Pastikan untuk menyesuaikan nama kolom dan logika yang sesuai dengan struktur Anda
    $jamAbsen = $sekolah->jam_masuk; // Ubah sesuai dengan kolom yang sesuai
    $kolomJam = 'jam_masuk'; // Nama kolom untuk jam absen, bisa jam_masuk atau jam_pulang
    $status = 'masuk'; // Status default

    // Jika kolom absensi adalah jam_pulang, atur status menjadi pulang
    

    $jamAbsensi = $request->$kolomJam;
    $selisih = Carbon::parse($jamAbsensi)->diffInMinutes($jamAbsen);

    if ($selisih > 0) {
        $status = 'terlambat';
    }

    // Lokasi absensi fungsi
    // $lokasiSekolah = $sekolah->lokasi_absensi; // Ambil lokasi dari sekolah
    // $lokasiAbsensi = $request->lokasi_absensi;

    // if ($lokasiSekolah != $lokasiAbsensi) {
    //     return response()->json([
    //         'message' => 'Lokasi absensi tidak valid',
    //         'status' => 'lokasi_tidak_valid',
    //     ]);
    // }

         $absensi = new Absensi;
    $absensi->guru()->associate($guru);
    $absensi->sekolah()->associate($sekolah);
    $absensi->tanggal = $request->tanggal;
    $absensi->jam_masuk = $request->jam_masuk;
    $absensi->lokasi_absensi = $request->lokasi_absensi;
    $absensi->status = $status;
    $absensi->save();

        return response()->json([
            'message' => 'Absen masuk berhasil',
            'status' => $status,
        ]);
    }
    public function absenPulang(Request $request)
{
   $guru = Guru::find($request->guru_id);
    $sekolah = Sekolah::find($request->sekolah_id);

    // Cari entri terakhir yang sesuai dengan guru, sekolah, dan tanggal tertentu
    $absensi = Absensi::where('guru_id', $guru->id)
        ->where('sekolah_id', $sekolah->id)
        ->whereDate('tanggal', $request->tanggal)
        ->latest() // Ambil yang terbaru
        ->first();

    if (!$absensi) {
        return response()->json([
            'message' => 'Data absensi tidak ditemukan',
            'status' => 'data_tidak_ditemukan',
        ]);
    }

    // Jika sudah ada jam pulang, guru sudah melakukan absen pulang sebelumnya
    if ($absensi->jam_pulang !== null) {
        return response()->json([
            'message' => 'Guru sudah melakukan absen pulang sebelumnya',
            'status' => 'absen_pulang_sudah_dilakukan',
        ]);
    }

    // Pastikan untuk menyesuaikan nama kolom yang sesuai dengan struktur Anda
    $jamPulangSekolah = $sekolah->jam_absensi_pulang; // Ubah sesuai dengan kolom yang sesuai
    $kolomJam = 'jam_pulang'; // Nama kolom untuk jam pulang

    $jamPulang = $request->$kolomJam;
   

    // Update data absensi
    $absensi->jam_pulang = $request->$kolomJam;
   
    $absensi->save();

    return response()->json([
        'message' => 'Absen pulang berhasil',
        'status' => $status,
    ]);
}

}



