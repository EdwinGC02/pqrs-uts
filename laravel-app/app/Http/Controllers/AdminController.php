<?php

namespace App\Http\Controllers;

use App\Models\Dependencia;
use App\Models\Pqrs;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function dashboard(): Response
    {
        $stats = [
            'total'      => Pqrs::count(),
            'recibido'   => Pqrs::where('estado', 'recibido')->count(),
            'en_proceso' => Pqrs::where('estado', 'en_proceso')->count(),
            'resuelto'   => Pqrs::where('estado', 'resuelto')->count(),
            'cerrado'    => Pqrs::where('estado', 'cerrado')->count(),
        ];

        $porTipo = Pqrs::select('tipo', DB::raw('count(*) as total'))
            ->groupBy('tipo')
            ->pluck('total', 'tipo');

        $porMes = Pqrs::select(
            DB::raw("TO_CHAR(created_at, 'Mon') as mes"),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy(DB::raw("TO_CHAR(created_at, 'Mon')"), DB::raw("DATE_TRUNC('month', created_at)"))
            ->orderBy(DB::raw("DATE_TRUNC('month', created_at)"))
            ->get();

        $recientes = Pqrs::with('user', 'dependencia')
            ->where('estado', 'recibido')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($p) => $this->formatPqrs($p));

        return Inertia::render('Admin/Dashboard', compact('stats', 'porTipo', 'porMes', 'recientes'));
    }

    public function index(Request $request): Response
    {
        $query = Pqrs::with('user', 'dependencia')->latest();

        if ($request->filled('estado'))      $query->where('estado', $request->estado);
        if ($request->filled('tipo'))        $query->where('tipo', $request->tipo);
        if ($request->filled('dependencia')) $query->where('dependencia_id', $request->dependencia);
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('numero_ticket', 'ilike', "%{$request->search}%")
                  ->orWhere('asunto', 'ilike', "%{$request->search}%");
            });
        }

        $pqrs = $query->paginate(15)->through(fn ($p) => $this->formatPqrs($p));
        $dependencias = Dependencia::orderBy('nombre')->get(['id', 'nombre']);

        return Inertia::render('Admin/Solicitudes', [
            'pqrs'        => $pqrs,
            'dependencias' => $dependencias,
            'filters'     => $request->only('estado', 'tipo', 'dependencia', 'search'),
        ]);
    }

    public function update(Request $request, Pqrs $pqrs): RedirectResponse
    {
        $data = $request->validate([
            'estado'         => 'sometimes|in:recibido,en_proceso,resuelto,cerrado',
            'dependencia_id' => 'sometimes|nullable|exists:dependencias,id',
            'respuesta_admin' => 'sometimes|nullable|string',
        ]);

        $pqrs->update($data);

        return back()->with('success', 'Solicitud actualizada correctamente.');
    }

    public function usuarios(Request $request): Response
    {
        $users = User::query()
            ->when($request->search, fn ($q, $s) => $q->where('name', 'ilike', "%$s%")
                ->orWhere('email', 'ilike', "%$s%"))
            ->latest()
            ->paginate(20)
            ->through(fn ($u) => [
                'id'                => $u->id,
                'name'              => $u->name,
                'email'             => $u->email,
                'role'              => $u->role,
                'codigo_estudiante' => $u->codigo_estudiante,
                'programa'          => $u->programa,
                'created_at'        => $u->created_at->format('d/m/Y'),
            ]);

        return Inertia::render('Admin/Usuarios', [
            'users'   => $users,
            'filters' => $request->only('search'),
        ]);
    }

    public function updateUsuario(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => 'required|in:estudiante,admin',
        ]);

        $user->update($data);

        return back()->with('success', 'Rol actualizado correctamente.');
    }

    private function formatPqrs(Pqrs $p): array
    {
        return [
            'id'              => $p->id,
            'numero_ticket'   => $p->numero_ticket,
            'tipo'            => $p->tipo,
            'tipo_label'      => $p->tipoLabel(),
            'asunto'          => $p->asunto,
            'descripcion'     => $p->descripcion,
            'estado'          => $p->estado,
            'estado_label'    => $p->estadoLabel(),
            'canal'           => $p->canal,
            'respuesta_admin' => $p->respuesta_admin,
            'dependencia'     => $p->dependencia?->only('id', 'nombre'),
            'user'            => $p->user?->only('id', 'name', 'email'),
            'created_at'      => $p->created_at->format('d/m/Y H:i'),
        ];
    }
}
