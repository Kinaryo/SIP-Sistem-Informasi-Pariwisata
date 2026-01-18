<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class TourismPlace extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'location_id',
        'name',
        'slug',           // pastikan slug ada di fillable
        'description',
        'ticket_price',
        'open_time',
        'close_time',
        'contact',
        'cover_image',
        'is_active',
        'is_verified',
    ];

    /**
     * Boot method untuk auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Hanya generate slug jika belum ada
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
        return $this->belongsToMany(
            Facility::class,
            'tourism_facility' // nama pivot table
        );
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
}
