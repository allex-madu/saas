<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bakery extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'nif',
        'phone',
        'created_by',
        'trial_until',
        'admin_id',
    ];

    /**
     * Usuários pertencentes à padaria.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Usuário que criou a padaria (super-admin).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // app/Models/Bakery.php

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

}
