<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    //protected $table = 'cities';

    protected $fillable = [
        'state_id', 'title', 'iso', 'iso_ddd', 'status',
        'slug', 'population', 'lat', 'long', 'income_per_capita'
    ];
}
