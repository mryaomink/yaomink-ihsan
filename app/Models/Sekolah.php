<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    protected $table = 'sekolah'; // Nama tabel yang sesuai

    protected $fillable = [
        'nama_sekolah',
        'jam_masuk',
        'jam_pulang',
        'lokasi_koordinat',
        // Tambahkan kolom lain sesuai kebutuhan
    ];

    public function guru()
    {
        return $this->hasMany(Guru::class, 'sekolah_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'sekolah_id');
    }
}
