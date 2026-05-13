<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import StatusBadge from '@/Components/StatusBadge.vue'

defineProps({
  pqrs: { type: Object, required: true },
})
</script>

<template>
  <AppLayout>
    <Head :title="pqrs.numero_ticket" />

    <div class="max-w-2xl mx-auto space-y-4">
      <!-- Breadcrumb -->
      <div class="flex items-center gap-2 text-sm text-gray-500">
        <Link :href="route('pqrs.index')" class="hover:text-blue-600">Mis Solicitudes</Link>
        <span>/</span>
        <span class="font-mono">{{ pqrs.numero_ticket }}</span>
      </div>

      <!-- Encabezado -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start justify-between gap-4 mb-4">
          <div>
            <p class="text-xs font-mono text-gray-400 mb-1">{{ pqrs.numero_ticket }}</p>
            <h1 class="text-xl font-bold text-gray-800">{{ pqrs.asunto }}</h1>
          </div>
          <StatusBadge :estado="pqrs.estado" />
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm mb-5">
          <div>
            <span class="text-gray-500">Tipo</span>
            <p class="font-medium text-gray-800 capitalize">{{ pqrs.tipo_label }}</p>
          </div>
          <div>
            <span class="text-gray-500">Canal</span>
            <p class="font-medium text-gray-800 capitalize">{{ pqrs.canal }}</p>
          </div>
          <div>
            <span class="text-gray-500">Dependencia</span>
            <p class="font-medium text-gray-800">{{ pqrs.dependencia?.nombre ?? 'Sin asignar' }}</p>
          </div>
          <div>
            <span class="text-gray-500">Fecha</span>
            <p class="font-medium text-gray-800">{{ pqrs.created_at }}</p>
          </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
          <p class="text-gray-500 text-sm mb-2">Descripción</p>
          <p class="text-gray-800 text-sm leading-relaxed whitespace-pre-wrap">{{ pqrs.descripcion }}</p>
        </div>
      </div>

      <!-- Respuesta admin -->
      <div v-if="pqrs.respuesta_admin" class="bg-green-50 border border-green-200 rounded-2xl p-5">
        <p class="text-sm font-semibold text-green-800 mb-2">✅ Respuesta de la institución</p>
        <p class="text-green-900 text-sm leading-relaxed whitespace-pre-wrap">{{ pqrs.respuesta_admin }}</p>
      </div>

      <div v-else class="bg-blue-50 border border-blue-200 rounded-2xl p-5 text-center text-blue-700 text-sm">
        Tu solicitud está siendo revisada. Te notificaremos cuando haya una respuesta.
      </div>

      <div class="flex justify-center">
        <Link :href="route('pqrs.index')" class="text-sm text-gray-500 hover:text-blue-600">
          ← Volver al listado
        </Link>
      </div>
    </div>
  </AppLayout>
</template>
