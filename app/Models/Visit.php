<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'tourism_place_id',
        'visitor_count',
        'visit_date'
    ];

    public function tourismPlace()
    {
        return $this->belongsTo(TourismPlace::class);
    }
}
