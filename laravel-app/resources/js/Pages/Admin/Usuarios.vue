<script setup>
import { Head, router, useForm } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  users:   { type: Object, required: true },
  filters: { type: Object, default: () => ({}) },
})

const search = ref(props.filters.search ?? '')
let timeout = null

watch(search, (val) => {
  clearTimeout(timeout)
  timeout = setTimeout(() => {
    router.get(route('admin.usuarios'), { search: val || undefined }, { preserveState: true, replace: true })
  }, 300)
})

function changeRole(user, newRole) {
  if (!confirm(`¿Cambiar rol de "${user.name}" a "${newRole}"?`)) return
  const form = useForm({ role: newRole })
  form.put(route('admin.usuarios.update', user.id))
}
</script>

<template>
  <AdminLayout>
    <Head title="Gestión de Usuarios" />

    <div class="max-w-5xl mx-auto space-y-5">
      <h1 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h1>

      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <input v-model="search" type="text" placeholder="Buscar por nombre o correo..."
          class="w-full max-w-sm border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div v-if="users.data.length" class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Nombre</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Correo</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Código</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Programa</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Rol</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Registro</th>
                <th class="px-4 py-3"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="u in users.data" :key="u.id" class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ u.name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ u.email }}</td>
                <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ u.codigo_estudiante ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ u.programa ?? '—' }}</td>
                <td class="px-4 py-3">
                  <span :class="[
                    'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium',
                    u.role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'
                  ]">
                    {{ u.role === 'admin' ? 'Admin' : 'Estudiante' }}
                  </span>
                </td>
                <td class="px-4 py-3 text-gray-400 text-xs">{{ u.created_at }}</td>
                <td class="px-4 py-3">
                  <button
                    v-if="u.role === 'estudiante'"
                    @click="changeRole(u, 'admin')"
                    class="text-xs text-purple-600 hover:underline"
                  >→ Admin</button>
                  <button
                    v-else
                    @click="changeRole(u, 'estudiante')"
                    class="text-xs text-blue-600 hover:underline"
                  >→ Estudiante</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else class="p-8 text-center text-gray-400">No se encontraron usuarios</div>
      </div>
    </div>
  </AdminLayout>
</template>
