<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_toko',
        'slug',
        'deskripsi',
        'logo',
        'telepon',
        'telepon_aktif',
        'image_public_id'
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cek apakah telepon ditampilkan
    public function isTeleponTampil()
    {
        return $this->telepon_aktif && !empty($this->telepon);
    }
}