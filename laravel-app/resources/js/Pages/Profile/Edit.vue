<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const page = usePage()
const user = page.props.auth.user

const form = useForm({
  name:  user.name,
  email: user.email,
})

function update() {
  form.patch(route('profile.update'))
}
</script>

<template>
  <AppLayout>
    <Head title="Mi Perfil" />
    <div class="max-w-lg mx-auto">
      <h1 class="text-2xl font-bold text-gray-800 mb-6">Mi Perfil</h1>
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form @submit.prevent="update" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input v-model="form.name" type="text" required
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Correo</label>
            <input v-model="form.email" type="email" required
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </div>
          <button type="submit" :disabled="form.processing"
            class="bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white font-semibold px-5 py-2 rounded-lg transition">
            Guardar cambios
          </button>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
