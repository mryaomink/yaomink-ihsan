<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id', 'device_id',
    ];

    public function user()
    {
        return $this->belongsTo(Guru::class);
    }
}
