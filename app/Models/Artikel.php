<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Artikel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul',
        'slug',
        'isi',
        'gambar',
        'image_public_id',
        'is_active',
        'is_verified',
    ];

    /* ================= BOOT ================= */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($artikel) {
            if (!$artikel->slug) {
                $artikel->slug = Str::slug($artikel->judul);
            }
        });
    }

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

    public function getShortIsiAttribute()
    {
        return Str::limit(strip_tags($this->isi), 120);
    }

    public function getPenulisAttribute()
    {
        return $this->user->name ?? 'Unknown';
    }
}