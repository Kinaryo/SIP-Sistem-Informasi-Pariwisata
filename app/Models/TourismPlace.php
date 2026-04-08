<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class TourismPlace extends Model
{
    use HasFactory;

    /* ================= FILLABLE ================= */

    protected $fillable = [
        'user_id',
        'category_id',
        'location_id',
        'name',
        'slug',
        'description',
        'ticket_price',
        'open_time',
        'close_time',
        'contact',
        'cover_image',
        'is_active',
        'is_verified',
    ];

    /* ================= BOOT ================= */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    /* ================= RELATIONS ================= */

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'tourism_facility');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
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

    public function scopePopular($query)
    {
        return $query->withCount('visits')
                     ->orderByDesc('visits_count');
    }

    /* ================= ACCESSOR ================= */

    public function getFullAddressAttribute()
    {
        return $this->location
            ? $this->location->province . ', ' . $this->location->city
            : '-';
    }

    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1) ?? 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }

    public function getTotalVisitsAttribute()
    {
        return $this->visits()->count();
    }

    public function getStatusLabelAttribute()
    {
        if (!$this->is_verified) {
            return 'Menunggu Verifikasi';
        }

        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }
}