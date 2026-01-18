<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteAccessCount extends Model
{
    use HasFactory;

    protected $fillable = ['total_access'];
}
