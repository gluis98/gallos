<template>
  <div class="dropdown">
    <button
      type="button"
      class="btn btn-outline-secondary btn-sm position-relative"
      @click="toggle"
    >
      🔔
      <span
        v-if="localCount > 0"
        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
      >{{ localCount }}</span>
    </button>
    <ul
      v-show="open"
      class="dropdown-menu show position-absolute end-0 mt-1 shadow"
      style="min-width: 280px; max-height: 320px; overflow-y: auto; z-index: 1050;"
    >
      <li class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
        <span class="small fw-semibold">Notificaciones</span>
        <button type="button" class="btn btn-link btn-sm p-0" @click="markAll">Marcar leídas</button>
      </li>
      <li v-if="loading" class="px-3 py-2 text-muted small">Cargando…</li>
      <li v-else-if="!items.length" class="px-3 py-2 text-muted small">Sin notificaciones</li>
      <template v-else>
        <li v-for="n in items" :key="n.id" class="px-3 py-2 border-bottom small">
          <button
            type="button"
            class="btn btn-link text-start text-decoration-none p-0 w-100"
            :class="{ 'fw-bold': !n.read_at }"
            @click="openOne(n)"
          >
            {{ n.data?.title || 'Aviso' }}<br>
            <span class="text-muted">{{ n.data?.body }}</span>
          </button>
        </li>
      </template>
    </ul>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'

const page = usePage()
const open = ref(false)
const loading = ref(false)
const items = ref([])
const localCount = ref(page.props.unread_notifications_count ?? 0)

watch(
  () => page.props.unread_notifications_count,
  (v) => {
    if (typeof v === 'number') localCount.value = v
  }
)

async function loadList() {
  loading.value = true
  try {
    const { data } = await axios.get('/api/notifications', {
      headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    })
    items.value = data
  } finally {
    loading.value = false
  }
}

function toggle() {
  open.value = !open.value
  if (open.value) loadList()
}

async function openOne(n) {
  if (!n.read_at) {
    await axios.post(`/api/notifications/${n.id}/read`, null, {
      headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    })
    n.read_at = new Date().toISOString()
    localCount.value = Math.max(0, localCount.value - 1)
  }
}

async function markAll() {
  await axios.post('/api/notifications/read-all', null, {
    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
  })
  localCount.value = 0
  await loadList()
}
</script>
