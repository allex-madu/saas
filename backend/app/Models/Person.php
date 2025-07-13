<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'people'; 
    
    protected $fillable = [
        'id',
        'name',
        'nickname',
        'nif',
        'address',
        'reference',
        'number',
        'zip_code',
        'city_id',
        'phone',
        'email',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'person_id');
    }
}
