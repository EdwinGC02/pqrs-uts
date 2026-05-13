<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
  name:              '',
  email:             '',
  password:          '',
  password_confirmation: '',
  codigo_estudiante: '',
  programa:          '',
})

const programas = [
  'Ingeniería de Sistemas',
  'Ingeniería Industrial',
  'Ingeniería Electrónica',
  'Administración de Empresas',
  'Contaduría Pública',
  'Arquitectura',
  'Tecnología en Gestión Empresarial',
  'Tecnología en Análisis y Desarrollo de Software',
]

function submit() {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <Head title="Registro" />

  <div class="min-h-screen bg-gradient-to-br from-blue-900 to-blue-700 flex items-center justify-center p-4">
    <div class="w-full max-w-lg">
      <div class="bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-6">
          <h1 class="text-3xl font-extrabold text-blue-900">Crear cuenta</h1>
          <p class="text-gray-500 text-sm mt-1">Unidades Tecnológicas de Santander</p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
            <input v-model="form.name" type="text" required autofocus
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="{ 'border-red-500': form.errors.name }" />
            <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Correo institucional</label>
            <input v-model="form.email" type="email" required
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="{ 'border-red-500': form.errors.email }" />
            <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Código estudiantil</label>
              <input v-model="form.codigo_estudiante" type="text" maxlength="20"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Programa</label>
              <select v-model="form.programa"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Seleccionar...</option>
                <option v-for="p in programas" :key="p" :value="p">{{ p }}</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
            <input v-model="form.password" type="password" required autocomplete="new-password"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="{ 'border-red-500': form.errors.password }" />
            <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
            <input v-model="form.password_confirmation" type="password" required autocomplete="new-password"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </div>

          <button type="submit" :disabled="form.processing"
            class="w-full bg-blue-900 hover:bg-blue-800 disabled:opacity-60 text-white font-semibold py-2.5 rounded-lg transition mt-2">
            {{ form.processing ? 'Registrando...' : 'Crear cuenta' }}
          </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-5">
          ¿Ya tienes cuenta?
          <Link :href="route('login')" class="text-blue-600 hover:underline font-medium">Inicia sesión</Link>
        </p>
      </div>
    </div>
  </div>
</template>
