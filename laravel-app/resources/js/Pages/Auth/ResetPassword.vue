<script setup>
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
  token: String,
  email: String,
})

const form = useForm({
  token:                 props.token,
  email:                 props.email,
  password:              '',
  password_confirmation: '',
})

function submit() {
  form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <Head title="Nueva contraseña" />
  <div class="min-h-screen bg-gradient-to-br from-blue-900 to-blue-700 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
      <h1 class="text-2xl font-bold text-blue-900 mb-6">Nueva contraseña</h1>
      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
          <input v-model="form.email" type="email" required
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.email }" />
          <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña</label>
          <input v-model="form.password" type="password" required
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.password }" />
          <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
          <input v-model="form.password_confirmation" type="password" required
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <button type="submit" :disabled="form.processing"
          class="w-full bg-blue-900 hover:bg-blue-800 disabled:opacity-60 text-white font-semibold py-2.5 rounded-lg transition">
          {{ form.processing ? 'Guardando...' : 'Restablecer contraseña' }}
        </button>
      </form>
    </div>
  </div>
</template>
