<template>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0">Panel de negocio</h1>
        <small class="text-muted">Resumen operativo, financiero y accesos rapidos</small>
      </div>
      <NotificationBell />
    </div>
    <div class="row g-3">
      <div class="col-md-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <h6 class="text-muted">Total aves</h6>
            <p class="display-6">{{ stats.aves_total }}</p>
            <small v-if="stats.limite_aves">Plan gratuito: máx. {{ stats.limite_aves }}</small>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <h6 class="text-muted">Gallos</h6>
            <p class="display-6">{{ stats.gallos }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <h6 class="text-muted">Gallinas</h6>
            <p class="display-6">{{ stats.gallinas }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <h6 class="text-muted">Ventas (mes)</h6>
            <p class="display-6">{{ stats.ventas_mes }}</p>
            <small>Plan: {{ stats.plan }}</small>
          </div>
        </div>
      </div>
    </div>
    <div class="row g-3 mt-1">
      <div class="col-md-3">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h6 class="text-muted">Compras (mes)</h6>
            <p class="h3 mb-0">{{ stats.compras_mes }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h6 class="text-muted">Ingresos</h6>
            <p class="h3 mb-0">${{ money(stats.ingresos_mes) }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h6 class="text-muted">Egresos</h6>
            <p class="h3 mb-0">${{ money(stats.egresos_mes) }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm h-100 border" :class="stats.balance_mes >= 0 ? 'border-success' : 'border-danger'">
          <div class="card-body">
            <h6 class="text-muted">Balance mensual</h6>
            <p class="h3 mb-0" :class="stats.balance_mes >= 0 ? 'text-success' : 'text-danger'">
              ${{ money(stats.balance_mes) }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm mt-3">
      <div class="card-body">
        <h6 class="text-muted mb-2">Accesos rapidos</h6>
        <div class="d-flex flex-wrap gap-2">
          <a class="btn btn-primary btn-sm" href="/gallos">Gestionar gallos</a>
          <a class="btn btn-primary btn-sm" href="/gallinas">Gestionar gallinas</a>
          <a class="btn btn-outline-primary btn-sm" href="/compras">Registrar compras</a>
          <a class="btn btn-outline-primary btn-sm" href="/ventas">Registrar ventas</a>
          <a class="btn btn-outline-secondary btn-sm" href="/marketplace">Marketplace</a>
          <a class="btn btn-outline-secondary btn-sm" href="/plans">Planes y upgrade</a>
        </div>
      </div>
    </div>

    <div class="row g-3 mt-2">
      <div class="col-lg-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h6 class="text-muted mb-3">Aves por estatus</h6>
            <canvas v-if="hasEstatus" ref="estatusCanvas" height="220"></canvas>
            <p v-else class="text-muted small mb-0">Sin datos aún.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h6 class="text-muted mb-3">Ingresos vs egresos (6 meses)</h6>
            <canvas v-if="hasVentas" ref="ventasCanvas" height="220"></canvas>
            <p v-else class="text-muted small mb-0">Sin ventas registradas.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="row g-3 mt-2">
      <div class="col-lg-6">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h6 class="text-muted mb-3">Ultimas ventas</h6>
            <div v-if="recentVentas.length" class="table-responsive">
              <table class="table table-sm align-middle mb-0">
                <thead><tr><th>#</th><th>Fecha</th><th>Gallo</th><th>Cliente</th><th class="text-end">Monto</th></tr></thead>
                <tbody>
                  <tr v-for="v in recentVentas" :key="`v-${v.id}`">
                    <td>{{ v.id }}</td><td>{{ v.fecha }}</td><td>{{ v.gallo }}</td><td>{{ v.cliente }}</td>
                    <td class="text-end">${{ money(v.monto) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <p v-else class="text-muted small mb-0">Sin ventas recientes.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h6 class="text-muted mb-3">Ultimas compras</h6>
            <div v-if="recentCompras.length" class="table-responsive">
              <table class="table table-sm align-middle mb-0">
                <thead><tr><th>#</th><th>Fecha</th><th>Proveedor</th><th class="text-end">Total</th></tr></thead>
                <tbody>
                  <tr v-for="c in recentCompras" :key="`c-${c.id}`">
                    <td>{{ c.id }}</td><td>{{ c.fecha }}</td><td>{{ c.proveedor }}</td>
                    <td class="text-end">${{ money(c.total) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <p v-else class="text-muted small mb-0">Sin compras recientes.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import Chart from 'chart.js/auto'
import NotificationBell from '../Components/NotificationBell.vue'

const props = defineProps({
  stats: Object,
  charts: Object,
  recent: Object,
})

const estatusCanvas = ref(null)
const ventasCanvas = ref(null)
let chartEstatus = null
let chartVentas = null

const hasEstatus = computed(
  () => (props.charts?.estatus_values || []).some((v) => Number(v) > 0)
)
const hasVentas = computed(
  () => (props.charts?.ventas_values || []).some((v) => Number(v) > 0)
)
const recentVentas = computed(() => props.recent?.ventas || [])
const recentCompras = computed(() => props.recent?.compras || [])

onMounted(() => {
  if (hasEstatus.value && estatusCanvas.value) {
    chartEstatus = new Chart(estatusCanvas.value, {
      type: 'doughnut',
      data: {
        labels: props.charts.estatus_labels,
        datasets: [
          {
            data: props.charts.estatus_values,
            backgroundColor: ['#0d6efd', '#198754', '#dc3545', '#ffc107', '#6c757d'],
          },
        ],
      },
      options: { plugins: { legend: { position: 'bottom' } } },
    })
  }
  if (hasVentas.value && ventasCanvas.value) {
    chartVentas = new Chart(ventasCanvas.value, {
      type: 'line',
      data: {
        labels: props.charts.ventas_labels,
        datasets: [
          {
            label: 'Ingresos',
            data: props.charts.ventas_values,
            borderColor: 'rgba(13, 110, 253, 1)',
            backgroundColor: 'rgba(13, 110, 253, 0.15)',
            tension: 0.25,
            fill: true,
          },
          {
            label: 'Egresos',
            data: props.charts.compras_values || [],
            borderColor: 'rgba(220, 53, 69, 1)',
            backgroundColor: 'rgba(220, 53, 69, 0.1)',
            tension: 0.25,
            fill: true,
          },
        ],
      },
      options: {
        scales: { y: { beginAtZero: true } },
        plugins: { legend: { display: true, position: 'bottom' } },
      },
    })
  }
})

onBeforeUnmount(() => {
  chartEstatus?.destroy()
  chartVentas?.destroy()
})

function money(value) {
  return Number(value || 0).toFixed(2)
}
</script>
