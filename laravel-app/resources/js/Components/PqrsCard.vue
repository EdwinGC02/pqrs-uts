<script setup>
import { Link } from '@inertiajs/vue3'
import StatusBadge from '@/Components/StatusBadge.vue'

defineProps({
  pqrs: { type: Object, required: true },
})
</script>

<template>
  <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 hover:shadow-md transition">
    <div class="flex items-start justify-between gap-3">
      <div class="flex-1 min-w-0">
        <p class="text-xs text-gray-400 font-mono">{{ pqrs.numero_ticket }}</p>
        <p class="font-semibold text-gray-800 truncate mt-0.5">{{ pqrs.asunto }}</p>
        <p class="text-xs text-gray-500 mt-1 capitalize">
          {{ pqrs.tipo_label }}
          <span v-if="pqrs.dependencia" class="mx-1">·</span>
          <span v-if="pqrs.dependencia">{{ pqrs.dependencia.nombre }}</span>
        </p>
      </div>
      <StatusBadge :estado="pqrs.estado" />
    </div>
    <div class="flex items-center justify-between mt-3">
      <span class="text-xs text-gray-400">{{ pqrs.created_at }}</span>
      <Link
        :href="route('pqrs.show', pqrs.id)"
        class="text-xs text-blue-600 hover:text-blue-800 font-medium"
      >
        Ver detalle →
      </Link>
    </div>
  </div>
</template>
