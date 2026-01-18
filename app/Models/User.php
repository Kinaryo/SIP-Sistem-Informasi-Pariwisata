<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Review;
use App\Models\Favorite;
use App\Models\EventRegistration;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [

        'name',
        'email',
        'password',
        'address',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];



    public function tourismPlaces()
    {
        return $this->hasMany(TourismPlace::class);
    }

    public function quizResults()
    {
        return $this->hasMany(QuizResult::class);
    }


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}
