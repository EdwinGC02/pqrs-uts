<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import StatsCard from '@/Components/StatsCard.vue'
import StatusBadge from '@/Components/StatusBadge.vue'

defineProps({
  stats:    { type: Object, required: true },
  porTipo:  { type: Object, default: () => ({}) },
  porMes:   { type: Array,  default: () => [] },
  recientes: { type: Array, default: () => [] },
})

const tipoLabels = {
  peticion: 'Petición', queja: 'Queja', reclamo: 'Reclamo', solicitud: 'Solicitud'
}
</script>

<template>
  <AdminLayout>
    <Head title="Dashboard Admin" />

    <div class="max-w-7xl mx-auto space-y-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Administrativo</h1>
        <p class="text-gray-500 text-sm">Resumen general del sistema PQRS</p>
      </div>

      <!-- Stats generales -->
      <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <StatsCard label="Total"       :value="stats.total"      color="blue"   icon="📋" />
        <StatsCard label="Recibidas"   :value="stats.recibido"   color="blue"   icon="📩" />
        <StatsCard label="En proceso"  :value="stats.en_proceso" color="yellow" icon="⏳" />
        <StatsCard label="Resueltas"   :value="stats.resuelto"   color="green"  icon="✅" />
        <StatsCard label="Cerradas"    :value="stats.cerrado"    color="gray"   icon="🔒" />
      </div>

      <div class="grid lg:grid-cols-2 gap-6">
        <!-- Por tipo -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
          <h2 class="font-semibold text-gray-700 mb-4">Distribución por tipo</h2>
          <div class="space-y-3">
            <div v-for="(total, tipo) in porTipo" :key="tipo" class="flex items-center gap-3">
              <span class="text-sm text-gray-600 w-24 capitalize">{{ tipoLabels[tipo] ?? tipo }}</span>
              <div class="flex-1 bg-gray-100 rounded-full h-2">
                <div
                  class="bg-blue-500 h-2 rounded-full"
                  :style="{ width: stats.total > 0 ? (total / stats.total * 100) + '%' : '0%' }"
                />
              </div>
              <span class="text-sm font-medium text-gray-700 w-8 text-right">{{ total }}</span>
            </div>
          </div>
        </div>

        <!-- Por mes -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
          <h2 class="font-semibold text-gray-700 mb-4">PQRS por mes (últimos 6 meses)</h2>
          <div v-if="porMes.length" class="flex items-end gap-2 h-32">
            <div v-for="m in porMes" :key="m.mes"
                 class="flex-1 flex flex-col items-center gap-1">
              <span class="text-xs text-gray-500 font-medium">{{ m.total }}</span>
              <div
                class="w-full bg-blue-500 rounded-t transition-all"
                :style="{ height: Math.max(8, (m.total / Math.max(...porMes.map(x=>x.total))) * 100) + 'px' }"
              />
              <span class="text-xs text-gray-400">{{ m.mes }}</span>
            </div>
          </div>
          <p v-else class="text-center text-gray-400 text-sm py-8">Sin datos suficientes</p>
        </div>
      </div>

      <!-- Recientes sin atender -->
      <div>
        <div class="flex items-center justify-between mb-3">
          <h2 class="font-semibold text-gray-700">Solicitudes recientes sin atender</h2>
          <Link :href="route('admin.solicitudes')" class="text-sm text-blue-600 hover:underline">Ver todas →</Link>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <div v-if="recientes.length" class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th class="text-left px-4 py-3 text-gray-500 font-medium">Ticket</th>
                  <th class="text-left px-4 py-3 text-gray-500 font-medium">Estudiante</th>
                  <th class="text-left px-4 py-3 text-gray-500 font-medium">Asunto</th>
                  <th class="text-left px-4 py-3 text-gray-500 font-medium">Tipo</th>
                  <th class="text-left px-4 py-3 text-gray-500 font-medium">Fecha</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr v-for="p in recientes" :key="p.id" class="hover:bg-gray-50">
                  <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ p.numero_ticket }}</td>
                  <td class="px-4 py-3 text-gray-700">{{ p.user?.name }}</td>
                  <td class="px-4 py-3 max-w-xs truncate text-gray-800">{{ p.asunto }}</td>
                  <td class="px-4 py-3 capitalize text-gray-600">{{ p.tipo_label }}</td>
                  <td class="px-4 py-3 text-gray-400 text-xs">{{ p.created_at }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="p-8 text-center text-gray-400">
            <p>✅ No hay solicitudes pendientes</p>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
