<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Review;
use App\Models\Favorite;
use App\Models\EventRegistration;
    use App\Notifications\CustomResetPassword;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [

        'name',
        'email',
        'password',
        'address',
        'role',
        'is_active',
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

    public function produks()
    {
        return $this->hasMany(Produk::class);
    }

    public function artikels()
    {
        return $this->hasMany(Artikel::class);
    }

    public function toko()
    {
        return $this->hasOne(Toko::class);
    }



public function sendPasswordResetNotification($token)
{
    $this->notify(new CustomResetPassword($token));
}
}
