<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MensajeChat extends Model
{
    public $timestamps = false;

    protected $table = 'mensajes_chat';

    protected $fillable = [
        'user_id',
        'pqrs_id',
        'remitente',
        'mensaje',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pqrs(): BelongsTo
    {
        return $this->belongsTo(Pqrs::class);
    }
}
