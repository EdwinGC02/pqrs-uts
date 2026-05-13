<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import StatsCard from '@/Components/StatsCard.vue'
import PqrsCard from '@/Components/PqrsCard.vue'

defineProps({
  stats:    { type: Object, required: true },
  recientes: { type: Array, required: true },
})
</script>

<template>
  <AppLayout>
    <Head title="Mi Dashboard" />

    <div class="max-w-5xl mx-auto space-y-6">
      <!-- Encabezado -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Mi Panel</h1>
          <p class="text-gray-500 text-sm">Resumen de tus solicitudes</p>
        </div>
        <Link
          :href="route('pqrs.create')"
          class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-lg transition"
        >
          <span class="text-lg">➕</span>
          Nueva solicitud
        </Link>
      </div>

      <!-- Estadísticas -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <StatsCard label="Total"      :value="stats.total"      color="blue"   icon="📋" />
        <StatsCard label="Recibidas"  :value="stats.recibido"   color="blue"   icon="📩" />
        <StatsCard label="En proceso" :value="stats.en_proceso" color="yellow" icon="⏳" />
        <StatsCard label="Resueltas"  :value="stats.resuelto"   color="green"  icon="✅" />
      </div>

      <!-- Últimas solicitudes -->
      <div>
        <div class="flex items-center justify-between mb-3">
          <h2 class="font-semibold text-gray-700">Últimas solicitudes</h2>
          <Link :href="route('pqrs.index')" class="text-sm text-blue-600 hover:underline">
            Ver todas →
          </Link>
        </div>

        <div v-if="recientes.length" class="space-y-3">
          <PqrsCard v-for="p in recientes" :key="p.id" :pqrs="p" />
        </div>
        <div v-else class="bg-white rounded-xl p-8 text-center text-gray-400 border border-dashed border-gray-300">
          <p class="text-4xl mb-2">📭</p>
          <p class="font-medium">Aún no tienes solicitudes</p>
          <p class="text-sm mt-1">Usa el chat o el formulario para registrar tu primera solicitud.</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
