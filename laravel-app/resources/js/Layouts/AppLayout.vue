<script setup>
import { ref } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import ChatWidget from '@/Components/ChatWidget.vue'

const page = usePage()
const sidebarOpen = ref(false)

function pathFromHref(href) {
  if (!href) return ''
  const clean = href.split('?')[0]
  if (clean.startsWith('/')) return clean
  try {
    return new URL(clean).pathname
  } catch {
    return clean.replace(/^https?:\/\/[^/]+/i, '') || ''
  }
}

function isNavItemActive(href) {
  const path = pathFromHref(href)
  return path !== '' && page.url.startsWith(path)
}

const navItems = [
  { name: 'Dashboard', href: route('dashboard'), icon: '📊' },
  { name: 'Nueva Solicitud', href: route('pqrs.create'), icon: '➕' },
  { name: 'Mis Solicitudes', href: route('pqrs.index'), icon: '📋' },
]

function logout() {
  router.post(route('logout'))
}
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex flex-col">
    <!-- Navbar -->
    <nav class="bg-blue-900 text-white shadow-lg z-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Logo + toggle -->
          <div class="flex items-center gap-3">
            <button
              class="lg:hidden p-1 rounded hover:bg-blue-800"
              @click="sidebarOpen = !sidebarOpen"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
            </button>
            <Link :href="route('dashboard')" class="flex items-center gap-2">
              <span class="text-2xl font-bold tracking-tight">PQRS</span>
              <span class="hidden sm:block text-blue-300 text-sm font-medium">UTS</span>
            </Link>
          </div>

          <!-- User menu -->
          <div class="flex items-center gap-4">
            <span class="text-blue-200 text-sm hidden sm:block">
              {{ page.props.auth.user?.name }}
            </span>
            <button
              @click="logout"
              class="text-sm bg-blue-800 hover:bg-blue-700 px-3 py-1.5 rounded transition"
            >
              Cerrar sesión
            </button>
          </div>
        </div>
      </div>
    </nav>

    <div class="flex flex-1">
      <!-- Sidebar -->
      <aside
        :class="[
          'bg-white shadow-md w-64 flex-shrink-0 transition-transform duration-200 lg:translate-x-0 lg:static',
          'fixed inset-y-0 left-0 z-10 pt-16',
          sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
        ]"
      >
        <nav class="p-4 space-y-1 mt-2">
          <Link
            v-for="item in navItems"
            :key="item.href"
            :href="item.href"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition group"
            :class="{ 'bg-blue-50 text-blue-700 font-medium': isNavItemActive(item.href) }"
            @click="sidebarOpen = false"
          >
            <span class="text-lg">{{ item.icon }}</span>
            <span class="text-sm">{{ item.name }}</span>
          </Link>
        </nav>
      </aside>

      <!-- Overlay -->
      <div
        v-if="sidebarOpen"
        class="fixed inset-0 bg-black/30 z-0 lg:hidden"
        @click="sidebarOpen = false"
      />

      <!-- Main content -->
      <main class="flex-1 p-6 overflow-auto">
        <!-- Flash messages -->
        <div v-if="page.props.flash?.success" class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
          {{ page.props.flash.success }}
        </div>
        <div v-if="page.props.flash?.error" class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
          {{ page.props.flash.error }}
        </div>

        <slot />
      </main>
    </div>

    <!-- Chat Widget -->
    <ChatWidget />
  </div>
</template>
