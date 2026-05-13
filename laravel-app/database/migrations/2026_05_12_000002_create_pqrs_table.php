<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pqrs', function (Blueprint $table) {
            $table->id();
            $table->string('numero_ticket', 20)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dependencia_id')->nullable()->constrained('dependencias')->nullOnDelete();
            $table->enum('tipo', ['peticion', 'queja', 'reclamo', 'solicitud']);
            $table->string('asunto');
            $table->text('descripcion');
            $table->enum('estado', ['recibido', 'en_proceso', 'resuelto', 'cerrado'])->default('recibido');
            $table->enum('canal', ['formulario', 'chat'])->default('formulario');
            $table->text('respuesta_admin')->nullable();
            $table->boolean('clasificado_por_ia')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pqrs');
    }
};
