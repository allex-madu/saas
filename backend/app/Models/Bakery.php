<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bakery extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Relação N:N com usuários pertencentes à padaria.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Criador da padaria (geralmente o super admin).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Administrador principal da padaria.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
