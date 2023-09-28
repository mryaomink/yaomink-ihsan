<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Guru extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'guru'; // Nama tabel yang sesuai

    protected $fillable = [
        'nama_guru',
        'nip_nik',
        'password',
        'device_id',
        'sekolah_id', // Jika Anda ingin menambahkan relasi ke sekolah
        // Tambahkan kolom lain sesuai kebutuhan
    ];

    protected $hidden = [
        'password', 'device_id',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'guru_id');
    }

    // public function devices()
    // {
    //     return $this->hasMany(Device::class);
    // }
}
