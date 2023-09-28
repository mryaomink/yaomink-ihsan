<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    protected $table = 'absensi'; // Nama tabel yang sesuai

    protected $fillable = [
        'guru_id',
        'sekolah_id',
        'status',
        'jam_masuk',
        'jam_pulang',
        'lokasi_absensi',
        'tanggal',
        // Tambahkan kolom lain sesuai kebutuhan
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }
}
