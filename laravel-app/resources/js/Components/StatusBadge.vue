<script setup>
import { computed } from 'vue'

const props = defineProps({
  estado: { type: String, required: true },
})

const config = {
  recibido:   { label: 'Recibido',    cls: 'bg-blue-100 text-blue-700' },
  en_proceso: { label: 'En proceso',  cls: 'bg-yellow-100 text-yellow-700' },
  resuelto:   { label: 'Resuelto',    cls: 'bg-green-100 text-green-700' },
  cerrado:    { label: 'Cerrado',     cls: 'bg-gray-100 text-gray-600' },
}

const badge = computed(() => config[props.estado] ?? { label: props.estado, cls: 'bg-gray-100 text-gray-600' })
</script>

<template>
  <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', badge.cls]">
    {{ badge.label }}
  </span>
</template>
