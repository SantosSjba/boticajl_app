<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
  fechaDesde: string;
  fechaHasta: string;
  filtroUsuario: number;
  listaUsuarios: Array<{ idusu: number; usuario: string; nombres: string | null }>;
  ventas: number;
  costos: number;
  ganancia: number;
  gastos: number;
  neto: number;
  simboloMoneda: string;
}>();

const fechaDesde = ref(props.fechaDesde);
const fechaHasta = ref(props.fechaHasta);
const filtroUsuario = ref(String(props.filtroUsuario));

function filtrar(): void {
  const params = new URLSearchParams();
  params.set('fecha_desde', fechaDesde.value);
  params.set('fecha_hasta', fechaHasta.value);
  if (filtroUsuario.value !== '0') params.set('filtro_usuario', filtroUsuario.value);
  router.get(`/dashboard?${params.toString()}`);
}

function mesActual(): void {
  router.get('/dashboard');
}

const usuarioFiltroNombre = () => {
  if (props.filtroUsuario === 0) return null;
  const u = props.listaUsuarios.find((x) => x.idusu === props.filtroUsuario);
  return u ? (u.nombres || u.usuario) : '—';
};

function formatDate(d: string): string {
  if (!d) return '—';
  const [y, m, day] = d.split('-');
  return `${day}/${m}/${y}`;
}
</script>

<template>
  <div
    class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6"
  >
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
      Resumen Financiero
    </h3>
    <form
      class="mt-4 flex flex-wrap items-end gap-3"
      @submit.prevent="filtrar"
    >
      <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Desde</label>
        <input
          v-model="fechaDesde"
          type="date"
          name="fecha_desde"
          class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800"
        />
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Hasta</label>
        <input
          v-model="fechaHasta"
          type="date"
          name="fecha_hasta"
          class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800"
        />
      </div>
      <div class="min-w-[180px]">
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Usuario</label>
        <div class="relative z-20 bg-transparent">
          <select
            v-model="filtroUsuario"
            name="filtro_usuario"
            class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800"
          >
            <option value="0">Todos (General)</option>
            <option
              v-for="u in listaUsuarios"
              :key="u.idusu"
              :value="String(u.idusu)"
            >
              {{ u.nombres || u.usuario }}
            </option>
          </select>
          <span class="pointer-events-none absolute right-4 top-1/2 z-30 -translate-y-1/2 text-gray-700 dark:text-gray-400">
            <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </span>
        </div>
      </div>
      <button
        type="submit"
        class="h-11 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-brand-600 disabled:cursor-not-allowed disabled:opacity-70"
      >
        Filtrar
      </button>
      <button
        type="button"
        class="inline-flex h-11 items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]"
        @click="mesActual"
      >
        Mes actual
      </button>
    </form>
    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
      <strong>Período:</strong> {{ formatDate(fechaDesde) }} – {{ formatDate(fechaHasta) }}
      <template v-if="filtroUsuario > 0">
        · <strong>Usuario:</strong> {{ usuarioFiltroNombre() }}
      </template>
      <template v-else>
        · <strong>Vista:</strong> Total empresa
      </template>
    </p>
    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-5">
      <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 text-center dark:border-gray-800 dark:bg-gray-800/50">
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Ventas</p>
        <p class="mt-1 text-lg font-bold text-gray-800 dark:text-white/90">
          {{ simboloMoneda }} {{ ventas.toLocaleString('es-PE', { minimumFractionDigits: 2 }) }}
        </p>
      </div>
      <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 text-center dark:border-gray-800 dark:bg-gray-800/50">
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Costo</p>
        <p class="mt-1 text-lg font-bold text-gray-800 dark:text-white/90">
          {{ simboloMoneda }} {{ costos.toLocaleString('es-PE', { minimumFractionDigits: 2 }) }}
        </p>
      </div>
      <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 text-center dark:border-gray-800 dark:bg-gray-800/50">
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Ganancia</p>
        <p class="mt-1 text-lg font-bold text-green-600 dark:text-green-400">
          {{ simboloMoneda }} {{ ganancia.toLocaleString('es-PE', { minimumFractionDigits: 2 }) }}
        </p>
      </div>
      <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 text-center dark:border-gray-800 dark:bg-gray-800/50">
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Gastos</p>
        <p class="mt-1 text-lg font-bold text-red-600 dark:text-red-400">
          {{ simboloMoneda }} {{ gastos.toLocaleString('es-PE', { minimumFractionDigits: 2 }) }}
        </p>
      </div>
      <div class="rounded-xl border-2 border-green-200 bg-green-50 p-4 text-center dark:border-green-800 dark:bg-green-900/20">
        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Neto</p>
        <p class="mt-1 text-lg font-bold text-green-700 dark:text-green-400">
          {{ simboloMoneda }} {{ neto.toLocaleString('es-PE', { minimumFractionDigits: 2 }) }}
        </p>
      </div>
    </div>
  </div>
</template>
