<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backery extends Model
{
    protected $fillable = ['name', 'nif'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
