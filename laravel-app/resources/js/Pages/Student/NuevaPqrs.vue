<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
  dependencias: { type: Array, default: () => [] },
})

const form = useForm({
  tipo:        '',
  asunto:      '',
  descripcion: '',
})

function submit() {
  form.post(route('pqrs.store'))
}

const tipos = [
  { value: 'peticion',  label: 'Petición',  desc: 'Solicitud de información o servicio' },
  { value: 'queja',     label: 'Queja',     desc: 'Inconformidad con un servicio recibido' },
  { value: 'reclamo',   label: 'Reclamo',   desc: 'Exigencia de un derecho desconocido' },
  { value: 'solicitud', label: 'Solicitud', desc: 'Requerimiento de trámite o proceso' },
]
</script>

<template>
  <AppLayout>
    <Head title="Nueva Solicitud" />

    <div class="max-w-2xl mx-auto">
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Nueva Solicitud</h1>
        <p class="text-gray-500 text-sm mt-1">Completa el formulario para registrar tu PQRS</p>
      </div>

      <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <form @submit.prevent="submit" class="space-y-5">

          <!-- Tipo -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de solicitud *</label>
            <div class="grid grid-cols-2 gap-3">
              <label
                v-for="t in tipos"
                :key="t.value"
                class="flex flex-col p-3 border-2 rounded-xl cursor-pointer transition"
                :class="form.tipo === t.value
                  ? 'border-blue-500 bg-blue-50'
                  : 'border-gray-200 hover:border-blue-300'"
              >
                <input v-model="form.tipo" type="radio" :value="t.value" class="sr-only" />
                <span class="font-semibold text-sm" :class="form.tipo === t.value ? 'text-blue-700' : 'text-gray-700'">
                  {{ t.label }}
                </span>
                <span class="text-xs text-gray-500 mt-0.5">{{ t.desc }}</span>
              </label>
            </div>
            <p v-if="form.errors.tipo" class="text-red-500 text-xs mt-1">{{ form.errors.tipo }}</p>
          </div>

          <!-- Asunto -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Asunto *</label>
            <input
              v-model="form.asunto"
              type="text"
              required
              maxlength="255"
              placeholder="Título breve de tu solicitud"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="{ 'border-red-500': form.errors.asunto }"
            />
            <p v-if="form.errors.asunto" class="text-red-500 text-xs mt-1">{{ form.errors.asunto }}</p>
          </div>

          <!-- Descripción -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción detallada *</label>
            <textarea
              v-model="form.descripcion"
              required
              rows="5"
              placeholder="Describe tu situación con el mayor detalle posible..."
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
              :class="{ 'border-red-500': form.errors.descripcion }"
            />
            <p v-if="form.errors.descripcion" class="text-red-500 text-xs mt-1">{{ form.errors.descripcion }}</p>
          </div>

          <div class="flex gap-3 pt-2">
            <button
              type="button"
              onclick="history.back()"
              class="flex-1 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2.5 rounded-lg transition"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="form.processing"
              class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white font-semibold py-2.5 rounded-lg transition"
            >
              {{ form.processing ? 'Enviando...' : 'Registrar solicitud' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
