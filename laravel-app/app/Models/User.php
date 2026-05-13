<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'codigo_estudiante',
        'programa',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEstudiante(): bool
    {
        return $this->role === 'estudiante';
    }

    public function pqrs(): HasMany
    {
        return $this->hasMany(Pqrs::class);
    }

    public function mensajesChat(): HasMany
    {
        return $this->hasMany(MensajeChat::class);
    }
}
