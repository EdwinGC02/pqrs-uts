<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PqrsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Página principal — redirige según rol
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }
    return Inertia::render('Welcome');
})->name('home');

// ─── MÓDULO ESTUDIANTE ─────────────────────────────────
Route::middleware(['auth', 'isStudent'])->group(function () {
    Route::get('/dashboard', [PqrsController::class, 'dashboard'])->name('dashboard');
    Route::get('/pqrs/nueva', [PqrsController::class, 'create'])->name('pqrs.create');
    Route::post('/pqrs', [PqrsController::class, 'store'])->name('pqrs.store');
    Route::get('/pqrs/mis-solicitudes', [PqrsController::class, 'index'])->name('pqrs.index');
    Route::get('/pqrs/{pqrs}', [PqrsController::class, 'show'])->name('pqrs.show');
});

// ─── MÓDULO ADMINISTRADOR ──────────────────────────────
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/solicitudes', [AdminController::class, 'index'])->name('solicitudes');
    Route::put('/solicitudes/{pqrs}', [AdminController::class, 'update'])->name('solicitudes.update');
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios');
    Route::put('/usuarios/{user}', [AdminController::class, 'updateUsuario'])->name('usuarios.update');
});

// ─── PERFIL (Breeze) ───────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
