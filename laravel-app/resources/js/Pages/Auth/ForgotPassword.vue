<script setup>
import { Head, useForm } from '@inertiajs/vue3'

defineProps({ status: String })

const form = useForm({ email: '' })

function submit() {
  form.post(route('password.email'))
}
</script>

<template>
  <Head title="Recuperar contraseña" />
  <div class="min-h-screen bg-gradient-to-br from-blue-900 to-blue-700 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
      <h1 class="text-2xl font-bold text-blue-900 mb-2">Recuperar contraseña</h1>
      <p class="text-sm text-gray-500 mb-6">Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.</p>
      <div v-if="status" class="mb-4 text-sm text-green-600 bg-green-50 px-3 py-2 rounded-lg">{{ status }}</div>
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
          <input v-model="form.email" type="email" required autofocus
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.email }" />
          <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
        </div>
        <button type="submit" :disabled="form.processing"
          class="w-full bg-blue-900 hover:bg-blue-800 disabled:opacity-60 text-white font-semibold py-2.5 rounded-lg transition">
          {{ form.processing ? 'Enviando...' : 'Enviar enlace' }}
        </button>
      </form>
    </div>
  </div>
</template>
