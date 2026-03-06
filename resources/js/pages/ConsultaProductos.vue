<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const BASE = '/consulta/productos';

interface ProductoRow {
  idproducto: number;
  codigo: string;
  descripcion: string;
  tipo: string;
  stock: number;
  stockminimo: number;
  precio_venta: string | number;
  descuento: string | number;
  ventasujeta: string;
  estado: string;
  fecha_vencimiento: string | null;
  lote_numero: string;
  presentacion_nombre: string;
  sintoma_nombre: string;
}

interface PaginatorLink {
  url: string | null;
  label: string;
  active: boolean;
}

interface Paginator {
  data: ProductoRow[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  from: number | null;
  to: number | null;
  first_page_url: string;
  last_page_url: string;
  prev_page_url: string | null;
  next_page_url: string | null;
  path: string;
  links: PaginatorLink[];
}

const props = withDefaults(
  defineProps<{
    title: string;
    productos: Paginator;
    sort: string;
    direction: string;
    buscar: string;
    simboloMoneda: string;
  }>(),
  { buscar: '' }
);

const localBuscar = ref(props.buscar);

function buildQuery(params: { page?: number; sort?: string; direction?: string; buscar?: string }): string {
  const q = new URLSearchParams();
  if (params.buscar != null && params.buscar !== '') q.set('buscar', params.buscar);
  if (params.sort != null) q.set('sort', params.sort);
  if (params.direction != null) q.set('direction', params.direction);
  if (params.page != null && params.page > 1) q.set('page', String(params.page));
  const s = q.toString();
  return s ? `?${s}` : '';
}

function applySearch(): void {
  router.get(BASE + buildQuery({
    buscar: localBuscar.value.trim(),
    sort: props.sort,
    direction: props.direction,
  }));
}

function sortHref(col: string): string {
  const nextDir = props.sort === col && props.direction === 'asc' ? 'desc' : 'asc';
  return BASE + buildQuery({
    buscar: props.buscar,
    sort: col,
    direction: nextDir,
    page: 1,
  });
}

function sortIcon(col: string): string {
  if (props.sort !== col) return '';
  return props.direction === 'asc' ? '↑' : '↓';
}

const sortColumns = [
  { key: 'codigo', label: 'Código' },
  { key: 'descripcion', label: 'Descripción' },
  { key: 'presentacion', label: 'Presentación' },
  { key: 'fecha_vencimiento', label: 'Fec. venc' },
  { key: 'stock', label: 'Stock' },
  { key: 'precio_venta', label: 'P. venta' },
  { key: 'tipo', label: 'Tipo' },
  { key: 'estado', label: 'Estado' },
  { key: 'sintoma', label: 'Síntomas' },
  { key: 'lote', label: 'Lote' },
  { key: 'descuento', label: 'Descuento' },
  { key: 'ventasujeta', label: 'Venta sujeta' },
];

function formatDate(d: string | null | undefined): string {
  if (!d) return '—';
  const [y, m, day] = String(d).split('-');
  return `${day}/${m}/${y}`;
}

function formatMoney(n: number | string): string {
  return props.simboloMoneda + ' ' + Number(n).toLocaleString('es-PE', { minimumFractionDigits: 2 });
}

function estadoLabel(estado: string): string {
  return estado === '1' ? 'Activo' : 'Inactivo';
}

function ventasujetaLabel(v: string): string {
  return String(v).toLowerCase() === 'con receta medica' ? 'Sí' : 'No';
}

/** Traduce etiquetas de paginación de Laravel al español */
function paginationLabel(label: string): string {
  if (!label) return label;
  return label
    .replace(/\bPrevious\b/gi, 'Anterior')
    .replace(/\bNext\b/gi, 'Siguiente');
}

const debouncedApply = useDebounceFn(applySearch, 400);
watch(localBuscar, () => debouncedApply());
</script>

<template>
  <AppLayout>
    <Head :title="title" />
    <div class="min-w-0 space-y-6">
      <!-- Breadcrumb (estilo proyecto antiguo) -->
      <nav class="text-sm text-gray-500 dark:text-gray-400">
        <ol class="flex flex-wrap items-center gap-1">
          <li><Link href="/dashboard" class="hover:text-gray-700 dark:hover:text-gray-300">Inicio</Link></li>
          <li><span class="text-gray-400 dark:text-gray-500">/</span></li>
          <li><span class="text-gray-700 dark:text-gray-200">Consultas - Productos farmacéuticos</span></li>
        </ol>
      </nav>

      <!-- Card única: barra búsqueda + tabla + paginación (estilo proyecto antiguo) -->
      <div class="min-w-0 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <!-- Barra búsqueda y actualizar: título izquierda, Buscar/Actualizar derecha -->
        <div class="flex flex-col gap-4 border-b border-gray-200 px-4 py-4 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between sm:px-6">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Productos farmacéuticos
          </h3>
          <div class="flex flex-wrap items-center gap-3">
            <form
              class="flex flex-wrap items-center gap-2"
              @submit.prevent="applySearch"
            >
              <label for="input-buscar-productos" class="sr-only">Buscar</label>
              <input
                id="input-buscar-productos"
                v-model="localBuscar"
                type="search"
                name="buscar"
                placeholder="Código, descripción, presentación..."
                class="h-10 w-48 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 sm:w-56"
              />
              <button
                type="submit"
                class="inline-flex h-10 items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-600"
              >
                Buscar
              </button>
            </form>
            <Link
              :href="BASE + buildQuery({ buscar, sort, direction })"
              class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]"
              title="Actualizar datos"
            >
              <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              Actualizar
            </Link>
          </div>
        </div>

        <!-- Tabla (estilo proyecto antiguo: thead bg-gray-50, padding px-4 py-3, sm:pl-6 / sm:pr-6) -->
        <div class="min-w-0 w-full overflow-x-auto">
          <table class="min-w-full">
            <thead class="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
              <tr>
                <th
                  v-for="col in sortColumns"
                  :key="col.key"
                  :class="[
                    'px-4 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400',
                    col.key === 'codigo' ? 'sm:pl-6' : ''
                  ]"
                >
                  <Link
                    :href="sortHref(col.key)"
                    class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-300"
                  >
                    {{ col.label }}
                    <span v-if="sortIcon(col.key)" class="text-gray-400">{{ sortIcon(col.key) }}</span>
                  </Link>
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400 pr-4 sm:pr-6">
                  Similar
                </th>
              </tr>
            </thead>
            <tbody>
              <template v-if="productos.data && productos.data.length">
                <tr
                  v-for="p in productos.data"
                  :key="p.idproducto"
                  class="border-b border-gray-100 dark:border-gray-800"
                >
                  <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-800 dark:text-white/90 sm:pl-6">{{ p.codigo }}</td>
                  <td class="px-4 py-3 text-sm text-gray-800 dark:text-white/90">{{ p.descripcion }}</td>
                  <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ p.presentacion_nombre ?? '—' }}</td>
                  <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ formatDate(p.fecha_vencimiento) }}</td>
                  <td class="whitespace-nowrap px-4 py-3">
                    <span
                      :class="Number(p.stock) <= Number(p.stockminimo)
                        ? 'rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-400'
                        : 'rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400'"
                    >
                      {{ p.stock }}
                    </span>
                  </td>
                  <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ formatMoney(p.precio_venta) }}</td>
                  <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ p.tipo ?? '—' }}</td>
                  <td class="whitespace-nowrap px-4 py-3">
                    <span
                      :class="p.estado === '1'
                        ? 'rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400'
                        : 'rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-400'"
                    >
                      {{ estadoLabel(p.estado) }}
                    </span>
                  </td>
                  <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ p.sintoma_nombre ?? '—' }}</td>
                  <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ p.lote_numero ?? '—' }}</td>
                  <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ p.descuento ?? '—' }}</td>
                  <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ ventasujetaLabel(p.ventasujeta) }}</td>
                  <td class="whitespace-nowrap px-4 py-3 pr-4 sm:pr-6">
                    <Link
                      :href="`/en-desarrollo?idproducto=${p.idproducto}`"
                      class="inline-flex items-center rounded-lg bg-green-100 px-2.5 py-1.5 text-xs font-medium text-green-700 hover:bg-green-200 dark:bg-green-500/15 dark:text-green-500 dark:hover:bg-green-500/25"
                    >
                      Similar
                    </Link>
                  </td>
                </tr>
              </template>
              <tr v-else>
                <td colspan="13" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400 sm:pl-6">
                  No hay productos que coincidan con la búsqueda.
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Paginación: siempre mostrar resumen; botones solo cuando hay más de una página -->
        <div class="border-t border-gray-200 px-4 py-3 dark:border-gray-800 sm:px-6">
          <div class="flex flex-col items-center justify-between gap-3 sm:flex-row sm:gap-4">
            <p class="order-2 text-sm text-gray-600 dark:text-gray-400 sm:order-1">
              Mostrando {{ productos.from ?? 0 }} a {{ productos.to ?? 0 }} de {{ productos.total }} resultados
            </p>
            <nav
              v-if="productos.links && productos.links.length > 2"
              class="order-1 flex items-center gap-1.5 sm:order-2"
              aria-label="Paginación"
            >
              <template v-for="(link, i) in productos.links" :key="i">
                <Link
                  v-if="link.url"
                  :href="link.url"
                  :class="[
                    'inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-lg border px-2.5 text-sm font-medium transition',
                    link.active
                      ? 'border-brand-500 bg-brand-500 text-white shadow-sm'
                      : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-500 dark:hover:bg-gray-700'
                  ]"
                  v-html="paginationLabel(link.label)"
                />
                <span
                  v-else
                  class="inline-flex h-9 min-w-[2.25rem] cursor-default items-center justify-center rounded-lg border border-gray-200 bg-gray-50 px-2.5 text-sm text-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-500"
                  v-html="paginationLabel(link.label)"
                />
              </template>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
