<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import StatusBadge from '@/Components/StatusBadge.vue'

const props = defineProps({
  pqrs:         { type: Object, required: true },
  dependencias: { type: Array,  default: () => [] },
  filters:      { type: Object, default: () => ({}) },
})

// Filtros
const f = ref({ ...props.filters })
let searchTimeout = null

function applyFilters() {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    router.get(route('admin.solicitudes'), {
      estado:      f.value.estado      || undefined,
      tipo:        f.value.tipo        || undefined,
      dependencia: f.value.dependencia || undefined,
      search:      f.value.search      || undefined,
    }, { preserveState: true, replace: true })
  }, 300)
}

watch(f, applyFilters, { deep: true })

// Modal de respuesta
const modal = ref(null)

function openModal(pqrs) {
  modal.value = {
    id:             pqrs.id,
    numero_ticket:  pqrs.numero_ticket,
    asunto:         pqrs.asunto,
    form: useForm({
      estado:          pqrs.estado,
      dependencia_id:  pqrs.dependencia?.id ?? '',
      respuesta_admin: pqrs.respuesta_admin ?? '',
    }),
  }
}

function saveModal() {
  modal.value.form.put(route('admin.solicitudes.update', modal.value.id), {
    onSuccess: () => { modal.value = null },
  })
}
</script>

<template>
  <AdminLayout>
    <Head title="Gestión de Solicitudes" />

    <div class="max-w-7xl mx-auto space-y-5">
      <h1 class="text-2xl font-bold text-gray-800">Gestión de Solicitudes</h1>

      <!-- Filtros -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
          <input v-model="f.search" type="text" placeholder="Buscar ticket o asunto..."
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
          <select v-model="f.estado"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Todos los estados</option>
            <option value="recibido">Recibido</option>
            <option value="en_proceso">En proceso</option>
            <option value="resuelto">Resuelto</option>
            <option value="cerrado">Cerrado</option>
          </select>
          <select v-model="f.tipo"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Todos los tipos</option>
            <option value="peticion">Petición</option>
            <option value="queja">Queja</option>
            <option value="reclamo">Reclamo</option>
            <option value="solicitud">Solicitud</option>
          </select>
          <select v-model="f.dependencia"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Todas las dependencias</option>
            <option v-for="d in dependencias" :key="d.id" :value="d.id">{{ d.nombre }}</option>
          </select>
        </div>
      </div>

      <!-- Tabla -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div v-if="pqrs.data.length" class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Ticket</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Estudiante</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Asunto</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Tipo</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Dependencia</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Estado</th>
                <th class="text-left px-4 py-3 text-gray-500 font-medium">Fecha</th>
                <th class="px-4 py-3"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="p in pqrs.data" :key="p.id" class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ p.numero_ticket }}</td>
                <td class="px-4 py-3 text-gray-700">{{ p.user?.name }}</td>
                <td class="px-4 py-3 max-w-xs truncate font-medium text-gray-800">{{ p.asunto }}</td>
                <td class="px-4 py-3 capitalize text-gray-600">{{ p.tipo_label }}</td>
                <td class="px-4 py-3 text-gray-500">{{ p.dependencia?.nombre ?? '—' }}</td>
                <td class="px-4 py-3"><StatusBadge :estado="p.estado" /></td>
                <td class="px-4 py-3 text-gray-400 text-xs whitespace-nowrap">{{ p.created_at }}</td>
                <td class="px-4 py-3">
                  <button @click="openModal(p)"
                    class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                    Gestionar
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else class="p-12 text-center text-gray-400">
          <p class="text-3xl mb-2">🔍</p>
          <p>No se encontraron solicitudes</p>
        </div>

        <!-- Paginación -->
        <div v-if="pqrs.last_page > 1" class="px-4 py-3 border-t border-gray-200 flex items-center justify-between">
          <p class="text-xs text-gray-500">{{ pqrs.total }} resultados · Página {{ pqrs.current_page }} de {{ pqrs.last_page }}</p>
          <div class="flex gap-2">
            <Link v-if="pqrs.prev_page_url" :href="pqrs.prev_page_url" class="px-3 py-1 text-sm border rounded hover:bg-gray-50">← Anterior</Link>
            <Link v-if="pqrs.next_page_url" :href="pqrs.next_page_url" class="px-3 py-1 text-sm border rounded hover:bg-gray-50">Siguiente →</Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de gestión -->
    <Teleport to="body">
      <div v-if="modal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg">
          <div class="p-5 border-b border-gray-200">
            <h2 class="font-bold text-gray-800">Gestionar solicitud</h2>
            <p class="text-sm text-gray-500 font-mono">{{ modal.numero_ticket }} · {{ modal.asunto }}</p>
          </div>
          <div class="p-5 space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
              <select v-model="modal.form.estado"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="recibido">Recibido</option>
                <option value="en_proceso">En proceso</option>
                <option value="resuelto">Resuelto</option>
                <option value="cerrado">Cerrado</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Asignar dependencia</label>
              <select v-model="modal.form.dependencia_id"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sin asignar</option>
                <option v-for="d in dependencias" :key="d.id" :value="d.id">{{ d.nombre }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Respuesta</label>
              <textarea v-model="modal.form.respuesta_admin" rows="4" placeholder="Escribe la respuesta al estudiante..."
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" />
            </div>
          </div>
          <div class="p-5 border-t border-gray-200 flex gap-3">
            <button @click="modal = null"
              class="flex-1 border border-gray-300 hover:bg-gray-50 text-gray-700 py-2 rounded-lg transition text-sm">
              Cancelar
            </button>
            <button @click="saveModal" :disabled="modal.form.processing"
              class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white font-semibold py-2 rounded-lg transition text-sm">
              Guardar
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </AdminLayout>
</template>
