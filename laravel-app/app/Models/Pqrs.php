<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pqrs extends Model
{
    protected $table = 'pqrs';

    protected $fillable = [
        'numero_ticket',
        'user_id',
        'dependencia_id',
        'tipo',
        'asunto',
        'descripcion',
        'estado',
        'canal',
        'respuesta_admin',
        'clasificado_por_ia',
    ];

    protected function casts(): array
    {
        return [
            'clasificado_por_ia' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dependencia(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function mensajesChat(): HasMany
    {
        return $this->hasMany(MensajeChat::class);
    }

    public static function generarNumeroTicket(): string
    {
        $year  = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return sprintf('PQRS-%s-%05d', $year, $count);
    }

    public function estadoLabel(): string
    {
        return match ($this->estado) {
            'recibido'   => 'Recibido',
            'en_proceso' => 'En proceso',
            'resuelto'   => 'Resuelto',
            'cerrado'    => 'Cerrado',
            default      => $this->estado,
        };
    }

    public function tipoLabel(): string
    {
        return match ($this->tipo) {
            'peticion'  => 'Petición',
            'queja'     => 'Queja',
            'reclamo'   => 'Reclamo',
            'solicitud' => 'Solicitud',
            default     => $this->tipo,
        };
    }
}
