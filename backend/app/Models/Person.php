<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Person extends Model
{
    use HasFactory;

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
