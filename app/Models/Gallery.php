<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'tourism_place_id',
        'image',
        'title',
    ];

    public function tourismPlace()
    {
        return $this->belongsTo(TourismPlace::class);
    }
}
