<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dependencia extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'email_responsable'];

    public function pqrs(): HasMany
    {
        return $this->hasMany(Pqrs::class);
    }
}
