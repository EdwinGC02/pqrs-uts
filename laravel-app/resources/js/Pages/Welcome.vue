<script setup>
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
  <Head title="Bienvenido" />

  <div class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-blue-600 flex flex-col">
    <!-- Navbar -->
    <nav class="flex items-center justify-between px-6 py-4">
      <span class="text-3xl font-extrabold text-white tracking-tight">PQRS <span class="text-blue-300 text-lg font-normal">UTS</span></span>
      <div class="flex gap-3">
        <Link :href="route('login')"
          class="text-sm text-white border border-white/40 hover:bg-white/10 px-4 py-2 rounded-lg transition">
          Iniciar sesión
        </Link>
        <Link :href="route('register')"
          class="text-sm bg-white text-blue-900 font-semibold px-4 py-2 rounded-lg hover:bg-blue-50 transition">
          Registrarse
        </Link>
      </div>
    </nav>

    <!-- Hero -->
    <div class="flex-1 flex items-center justify-center px-6">
      <div class="max-w-3xl text-center">
        <div class="text-7xl mb-6">🎓</div>
        <h1 class="text-5xl font-extrabold text-white leading-tight mb-4">
          Sistema de Gestión de<br />
          <span class="text-blue-300">PQRS</span>
        </h1>
        <p class="text-blue-100 text-lg mb-2 leading-relaxed">
          Unidades Tecnológicas de Santander
        </p>
        <p class="text-blue-200 text-base mb-10">
          Registra, consulta y hace seguimiento a tus Peticiones, Quejas,
          Reclamos y Solicitudes de forma rápida y transparente.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Link :href="route('register')"
            class="bg-white text-blue-900 font-bold px-8 py-3.5 rounded-xl hover:bg-blue-50 transition text-lg shadow-lg">
            Registrarme ahora
          </Link>
          <Link :href="route('login')"
            class="border-2 border-white/60 text-white font-semibold px-8 py-3.5 rounded-xl hover:bg-white/10 transition text-lg">
            Ya tengo cuenta
          </Link>
        </div>
      </div>
    </div>

    <!-- Features -->
    <div class="bg-white/10 backdrop-blur-sm border-t border-white/20">
      <div class="max-w-4xl mx-auto px-6 py-10 grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
        <div>
          <div class="text-3xl mb-2">⚡</div>
          <h3 class="text-white font-bold mb-1">Rápido y simple</h3>
          <p class="text-blue-200 text-sm">Registra tu solicitud en menos de 2 minutos</p>
        </div>
        <div>
          <div class="text-3xl mb-2">🤖</div>
          <h3 class="text-white font-bold mb-1">Agente con IA</h3>
          <p class="text-blue-200 text-sm">Asistente inteligente disponible 24/7 para orientarte</p>
        </div>
        <div>
          <div class="text-3xl mb-2">📊</div>
          <h3 class="text-white font-bold mb-1">Trazabilidad total</h3>
          <p class="text-blue-200 text-sm">Sigue el estado de cada solicitud en tiempo real</p>
        </div>
      </div>
    </div>

    <footer class="text-center py-4 text-blue-300/60 text-xs">
      Unidades Tecnológicas de Santander — Sistema PQRS
    </footer>
  </div>
</template>
