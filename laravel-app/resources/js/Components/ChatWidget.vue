<script setup>
import { ref, nextTick } from 'vue'
import axios from 'axios'

const isOpen    = ref(false)
const loading   = ref(false)
const inputMsg  = ref('')
const messages  = ref([
  { remitente: 'agente', mensaje: '¡Hola! Soy el asistente virtual. ¿En qué puedo ayudarte hoy?' }
])
const messagesEl = ref(null)

function toggle() {
  isOpen.value = !isOpen.value
  if (isOpen.value) scrollDown()
}

async function send() {
  const texto = inputMsg.value.trim()
  if (!texto || loading.value) return

  messages.value.push({ remitente: 'usuario', mensaje: texto })
  inputMsg.value = ''
  loading.value  = true
  scrollDown()

  // Historial para el agente (últimos 10 mensajes)
  const historial = messages.value.slice(-10).map(m => ({
    role: m.remitente === 'usuario' ? 'user' : 'assistant',
    content: m.mensaje,
  }))

  try {
    const { data } = await axios.post('/api/chat', {
      mensaje:   texto,
      historial: historial,
    })

    messages.value.push({ remitente: 'agente', mensaje: data.respuesta })

    if (data.ticket) {
      messages.value.push({
        remitente: 'sistema',
        mensaje:   data.ticket,
      })
    }
  } catch {
    messages.value.push({
      remitente: 'agente',
      mensaje:   'Hubo un error al comunicarme. Por favor intenta de nuevo.',
    })
  } finally {
    loading.value = false
    scrollDown()
  }
}

function scrollDown() {
  nextTick(() => {
    if (messagesEl.value) {
      messagesEl.value.scrollTop = messagesEl.value.scrollHeight
    }
  })
}

function onKeydown(e) {
  if (e.key === 'Enter' && !e.shiftKey) {
    e.preventDefault()
    send()
  }
}
</script>

<template>
  <!-- Botón flotante -->
  <button
    @click="toggle"
    class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition-transform hover:scale-105"
    aria-label="Abrir chat"
  >
    <svg v-if="!isOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
    </svg>
    <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
    </svg>
  </button>

  <!-- Panel de chat -->
  <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 scale-95 translate-y-4"
              enter-to-class="opacity-100 scale-100 translate-y-0" leave-active-class="transition duration-150 ease-in"
              leave-from-class="opacity-100" leave-to-class="opacity-0 scale-95 translate-y-4">
    <div
      v-if="isOpen"
      class="fixed bottom-24 right-6 z-50 w-[350px] h-[500px] bg-white rounded-2xl shadow-2xl flex flex-col border border-gray-200 overflow-hidden"
    >
      <!-- Header -->
      <div class="bg-blue-900 text-white px-4 py-3 flex items-center gap-3">
        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-sm font-bold">A</div>
        <div>
          <p class="font-semibold text-sm">Asistente PQRS</p>
          <p class="text-blue-300 text-xs">Unidades Tecnológicas de Santander</p>
        </div>
      </div>

      <!-- Messages -->
      <div ref="messagesEl" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50">
        <div v-for="(msg, i) in messages" :key="i"
             :class="['flex', msg.remitente === 'usuario' ? 'justify-end' : 'justify-start']">

          <!-- Ticket creado -->
          <div v-if="msg.remitente === 'sistema'"
               class="w-full bg-green-50 border border-green-200 rounded-xl p-3 text-center">
            <p class="text-green-700 text-xs font-medium">✅ Ticket creado exitosamente</p>
            <p class="text-green-900 font-bold text-lg mt-1">{{ msg.mensaje }}</p>
          </div>

          <!-- Burbuja agente -->
          <div v-else-if="msg.remitente === 'agente'"
               class="max-w-[80%] bg-white border border-gray-200 rounded-2xl rounded-tl-sm px-3 py-2 shadow-sm">
            <p class="text-gray-800 text-sm leading-relaxed">{{ msg.mensaje }}</p>
          </div>

          <!-- Burbuja usuario -->
          <div v-else class="max-w-[80%] bg-blue-600 rounded-2xl rounded-tr-sm px-3 py-2">
            <p class="text-white text-sm leading-relaxed">{{ msg.mensaje }}</p>
          </div>
        </div>

        <!-- Indicador escribiendo -->
        <div v-if="loading" class="flex justify-start">
          <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-sm px-4 py-3">
            <div class="flex gap-1">
              <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0s"/>
              <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:.15s"/>
              <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:.3s"/>
            </div>
          </div>
        </div>
      </div>

      <!-- Input -->
      <div class="p-3 bg-white border-t border-gray-200 flex gap-2">
        <textarea
          v-model="inputMsg"
          @keydown="onKeydown"
          placeholder="Escribe tu mensaje..."
          rows="1"
          class="flex-1 resize-none rounded-xl border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          style="min-height:38px;max-height:100px"
        />
        <button
          @click="send"
          :disabled="loading || !inputMsg.trim()"
          class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white rounded-xl px-3 transition flex items-center"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
          </svg>
        </button>
      </div>
    </div>
  </Transition>
</template>
