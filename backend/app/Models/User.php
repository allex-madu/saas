<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $guard_name = 'web';

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relação com pessoa (dados pessoais).
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Relação N:N com padarias que o usuário pertence.
     */
    public function bakeries()
    {
        return $this->belongsToMany(Bakery::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Verifica se é super administrador.
     */
    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_super_admin;
    }

    
}
