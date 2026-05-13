<script setup>
defineProps({
  label:  { type: String,  required: true },
  value:  { type: Number,  required: true },
  color:  { type: String,  default: 'blue' },
  icon:   { type: String,  default: '📊' },
})

const colorMap = {
  blue:   'border-blue-500 bg-blue-50 text-blue-700',
  yellow: 'border-yellow-500 bg-yellow-50 text-yellow-700',
  green:  'border-green-500 bg-green-50 text-green-700',
  gray:   'border-gray-400 bg-gray-50 text-gray-600',
}
</script>

<template>
  <div class="bg-white rounded-xl shadow-sm border-l-4 p-5 flex items-center gap-4"
       :class="colorMap[color] ?? colorMap.blue">
    <span class="text-3xl">{{ icon }}</span>
    <div>
      <p class="text-2xl font-bold">{{ value }}</p>
      <p class="text-sm opacity-80">{{ label }}</p>
    </div>
  </div>
</template>
