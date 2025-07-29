<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';

    protected $fillable = [
        'state_id', 'title', 'iso', 'iso_ddd', 'status',
        'slug', 'population', 'lat', 'long', 'income_per_capita'
    ];

    public static function getCity()
    {
        return DB::table('cities')
            ->join('states', 'states.id', '=', 'cities.state_id')
            ->select([
                'cities.id',
                DB::raw("CONCAT(cities.title, ' - ', states.letter) as name")
            ]);
    }


    public function state()
    {
        return $this->belongsTo(State::class);
    }


}
