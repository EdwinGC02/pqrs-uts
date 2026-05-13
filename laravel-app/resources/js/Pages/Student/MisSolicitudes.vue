<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import StatusBadge from '@/Components/StatusBadge.vue'

const props = defineProps({
  pqrs:    { type: Object, required: true },
  filters: { type: Object, default: () => ({}) },
})

const estadoFiltro = ref(props.filters.estado ?? '')

watch(estadoFiltro, (val) => {
  router.get(route('pqrs.index'), { estado: val || undefined }, { preserveState: true, replace: true })
})

const estados = [
  { value: '',           label: 'Todos' },
  { value: 'recibido',   label: 'Recibido' },
  { value: 'en_proceso', label: 'En proceso' },
  { value: 'resuelto',   label: 'Resuelto' },
  { value: 'cerrado',    label: 'Cerrado' },
]
</script>

<template>
  <AppLayout>
    <Head title="Mis Solicitudes" />

    <div class="max-w-5xl mx-auto space-y-5">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Mis Solicitudes</h1>
          <p class="text-gray-500 text-sm">Historial completo de tus PQRS</p>
        </div>
        <Link :href="route('pqrs.create')"
          class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-lg transition">
          ➕ Nueva solicitud
        </Link>
      </div>

      <!-- Filtro -->
      <div class="flex gap-2 flex-wrap">
        <button
          v-for="e in estados"
          :key="e.value"
          @click="estadoFiltro = e.value"
          class="px-3 py-1.5 rounded-full text-sm font-medium transition border"
          :class="estadoFiltro === e.value
            ? 'bg-blue-600 text-white border-blue-600'
            : 'bg-white text-gray-600 border-gray-300 hover:border-blue-400'"
        >
          {{ e.label }}
        </button>
      </div>

      <!-- Tabla -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div v-if="pqrs.data.length" class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Ticket</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Tipo</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Asunto</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Dependencia</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Estado</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Fecha</th>
                <th class="px-4 py-3"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="p in pqrs.data" :key="p.id" class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ p.numero_ticket }}</td>
                <td class="px-4 py-3 capitalize">{{ p.tipo_label }}</td>
                <td class="px-4 py-3 max-w-xs truncate font-medium text-gray-800">{{ p.asunto }}</td>
                <td class="px-4 py-3 text-gray-500">{{ p.dependencia?.nombre ?? '—' }}</td>
                <td class="px-4 py-3"><StatusBadge :estado="p.estado" /></td>
                <td class="px-4 py-3 text-gray-400 text-xs whitespace-nowrap">{{ p.created_at }}</td>
                <td class="px-4 py-3">
                  <Link :href="route('pqrs.show', p.id)" class="text-blue-600 hover:underline text-xs font-medium">
                    Ver →
                  </Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-else class="p-12 text-center text-gray-400">
          <p class="text-4xl mb-2">📭</p>
          <p class="font-medium">No tienes solicitudes con este filtro</p>
        </div>

        <!-- Paginación -->
        <div v-if="pqrs.last_page > 1" class="px-4 py-3 border-t border-gray-200 flex items-center justify-between">
          <p class="text-xs text-gray-500">Página {{ pqrs.current_page }} de {{ pqrs.last_page }}</p>
          <div class="flex gap-2">
            <Link
              v-if="pqrs.prev_page_url"
              :href="pqrs.prev_page_url"
              class="px-3 py-1 text-sm border rounded hover:bg-gray-50"
            >← Anterior</Link>
            <Link
              v-if="pqrs.next_page_url"
              :href="pqrs.next_page_url"
              class="px-3 py-1 text-sm border rounded hover:bg-gray-50"
            >Siguiente →</Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
