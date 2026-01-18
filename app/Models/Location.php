<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'province',
        'city',
        'district',
        'address',
        'latitude',
        'longitude'
    ];

    public function tourismPlaces()
    {
        return $this->hasMany(TourismPlace::class);
    }
}
