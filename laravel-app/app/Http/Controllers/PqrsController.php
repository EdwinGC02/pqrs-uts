<?php

namespace App\Http\Controllers;

use App\Models\Dependencia;
use App\Models\Pqrs;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PqrsController extends Controller
{
    public function dashboard(Request $request): Response
    {
        $user  = $request->user();
        $pqrs  = $user->pqrs();

        $stats = [
            'total'      => $pqrs->count(),
            'recibido'   => $pqrs->where('estado', 'recibido')->count(),
            'en_proceso' => $pqrs->where('estado', 'en_proceso')->count(),
            'resuelto'   => $pqrs->where('estado', 'resuelto')->count(),
        ];

        $recientes = $user->pqrs()
            ->with('dependencia')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($p) => $this->formatPqrs($p));

        return Inertia::render('Student/Dashboard', compact('stats', 'recientes'));
    }

    public function create(): Response
    {
        $dependencias = Dependencia::orderBy('nombre')->get(['id', 'nombre']);
        return Inertia::render('Student/NuevaPqrs', compact('dependencias'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tipo'        => 'required|in:peticion,queja,reclamo,solicitud',
            'asunto'      => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        $pqrs = $request->user()->pqrs()->create([
            ...$data,
            'numero_ticket' => Pqrs::generarNumeroTicket(),
            'estado'        => 'recibido',
            'canal'         => 'formulario',
        ]);

        return redirect()->route('pqrs.show', $pqrs)
            ->with('success', "Solicitud registrada con ticket {$pqrs->numero_ticket}");
    }

    public function index(Request $request): Response
    {
        $query = $request->user()->pqrs()->with('dependencia')->latest();

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $pqrs = $query->paginate(10)->through(fn ($p) => $this->formatPqrs($p));

        return Inertia::render('Student/MisSolicitudes', [
            'pqrs'    => $pqrs,
            'filters' => $request->only('estado'),
        ]);
    }

    public function show(Pqrs $pqrs): Response
    {
        $this->authorize('view', $pqrs);

        $pqrs->load('dependencia', 'user');

        return Inertia::render('Student/ShowPqrs', [
            'pqrs' => $this->formatPqrs($pqrs),
        ]);
    }

    private function formatPqrs(Pqrs $p): array
    {
        return [
            'id'             => $p->id,
            'numero_ticket'  => $p->numero_ticket,
            'tipo'           => $p->tipo,
            'tipo_label'     => $p->tipoLabel(),
            'asunto'         => $p->asunto,
            'descripcion'    => $p->descripcion,
            'estado'         => $p->estado,
            'estado_label'   => $p->estadoLabel(),
            'canal'          => $p->canal,
            'respuesta_admin' => $p->respuesta_admin,
            'dependencia'    => $p->dependencia?->only('id', 'nombre'),
            'created_at'     => $p->created_at->format('d/m/Y H:i'),
        ];
    }
}
