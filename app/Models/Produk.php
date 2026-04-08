<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_produk',
        'deskripsi',
        'harga',
        'foto',
        'image_public_id',
        'is_active',
        'is_verified',
    ];

    /* ================= RELATION ================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* ================= SCOPE ================= */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeActiveVerified($query)
    {
        return $query->where('is_active', true)
            ->where('is_verified', true);
    }

    /* ================= ACCESSOR ================= */

    public function getTokoNamaAttribute()
    {
        return optional($this->user->toko)->nama_toko ?? 'Tidak ada toko';
    }

    public function getTokoLogoAttribute()
    {
        return optional($this->user->toko)->logo;
    }

    public function getTokoTeleponAttribute()
    {
        $toko = optional($this->user)->toko;

        return ($toko && $toko->telepon_aktif)
            ? $toko->telepon
            : null;
    }

    public function getTokoLabelAttribute()
    {
        if ($this->user->toko) {
            return $this->user->toko->nama_toko;
        }

        return $this->user->role === 'admin'
            ? 'Admin'
            : 'Tanpa Toko';
    }
}
