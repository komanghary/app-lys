<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TPresensi extends Model
{
    use HasFactory;

    protected $table = 't_presensis'; // Nama tabel kamu, biasanya jamak (optional kalau sesuai Laravel)

    protected $fillable = ['identitas_id', 'tanggal', 'jam_masuk', 'status', 'keterangan'];

    protected $dates = [
        'tanggal',
        'jam_masuk',
        'jam_keluar',
    ];

    // Relasi ke User (jika perlu)
    public function user()
    {
        return $this->belongsTo(User::class, 'identitas_id')->withTrashed();
    }
}
