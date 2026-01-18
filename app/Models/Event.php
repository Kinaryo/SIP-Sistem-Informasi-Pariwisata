<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'tourism_place_id',
        'title',
        'slug',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'poster'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }

    public function tourismPlace()
    {
        return $this->belongsTo(TourismPlace::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}
