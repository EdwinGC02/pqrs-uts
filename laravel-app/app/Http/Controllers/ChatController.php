<?php

namespace App\Http\Controllers;

use App\Models\Dependencia;
use App\Models\MensajeChat;
use App\Models\Pqrs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'mensaje'  => 'required|string|max:1000',
            'historial' => 'nullable|array',
        ]);

        $user     = $request->user();
        $mensaje  = $request->mensaje;
        $historial = $request->historial ?? [];

        // Guardar mensaje del usuario
        MensajeChat::create([
            'user_id'   => $user->id,
            'remitente' => 'usuario',
            'mensaje'   => $mensaje,
            'created_at' => now(),
        ]);

        // Llamar al webhook de n8n
        $n8nUrl = env('N8N_WEBHOOK_URL', 'http://n8n:5678');

        try {
            $response = Http::timeout(30)->post("{$n8nUrl}/webhook/pqrs-chat", [
                'mensaje'    => $mensaje,
                'historial'  => $historial,
                'user_id'    => $user->id,
                'user_name'  => $user->name,
            ]);

            if (! $response->successful()) {
                throw new \Exception('n8n respondió con error: '.$response->status());
            }

            $resultado = $response->json();

        } catch (\Exception $e) {
            Log::error('Error llamando a n8n: '.$e->getMessage());

            $fallback = 'Lo siento, el agente no está disponible en este momento. Por favor intenta más tarde o registra tu solicitud directamente en el formulario.';

            MensajeChat::create([
                'user_id'   => $user->id,
                'remitente' => 'agente',
                'mensaje'   => $fallback,
                'created_at' => now(),
            ]);

            return response()->json(['respuesta' => $fallback, 'ticket' => null]);
        }

        $respuestaTexto = $resultado['mensaje'] ?? 'No entendí tu mensaje. ¿Podrías reformularlo?';
        $crearTicket    = $resultado['crear_ticket'] ?? false;
        $ticket         = null;

        // Crear ticket automáticamente si n8n lo indica
        if ($crearTicket) {
            $dependenciaId = null;
            if (isset($resultado['dependencia'])) {
                $dep = Dependencia::where('nombre', 'like', '%'.$resultado['dependencia'].'%')->first();
                $dependenciaId = $dep?->id;
            }

            $pqrs = $user->pqrs()->create([
                'numero_ticket'     => Pqrs::generarNumeroTicket(),
                'tipo'              => $resultado['tipo_pqrs'] ?? 'solicitud',
                'asunto'            => substr($mensaje, 0, 200),
                'descripcion'       => $mensaje,
                'estado'            => 'recibido',
                'canal'             => 'chat',
                'dependencia_id'    => $dependenciaId,
                'clasificado_por_ia' => true,
            ]);

            $ticket = $pqrs->numero_ticket;
        }

        // Guardar respuesta del agente
        MensajeChat::create([
            'user_id'   => $user->id,
            'pqrs_id'   => isset($pqrs) ? $pqrs->id : null,
            'remitente' => 'agente',
            'mensaje'   => $respuestaTexto,
            'created_at' => now(),
        ]);

        return response()->json([
            'respuesta' => $respuestaTexto,
            'ticket'    => $ticket,
        ]);
    }
}
