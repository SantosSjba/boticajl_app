<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const props = withDefaults(
  defineProps<{
    title: string;
    items: Array<Record<string, unknown>>;
    simboloMoneda: string;
    variant: 'por-vencer' | 'bajo-stock';
  }>(),
  { variant: 'por-vencer' }
);

function formatDate(d: string | null | undefined): string {
  if (!d) return '—';
  const [y, m, day] = String(d).split('-');
  return `${day}/${m}/${y}`;
}

function formatMoney(n: number): string {
  return props.simboloMoneda + ' ' + Number(n).toLocaleString('es-PE', { minimumFractionDigits: 2 });
}

function estadoLabel(estado: unknown): string {
  return String(estado) === '1' ? 'Activo' : 'Inactivo';
}
</script>

<template>
  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="flex flex-col gap-3 border-b border-gray-200 px-4 py-4 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between sm:px-6">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
        {{ title }}
      </h3>
      <Link
        href="/dashboard"
        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
        title="Actualizar datos"
      >
        <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        Actualizar
      </Link>
    </div>
    <div class="max-w-full overflow-x-auto">
      <table class="min-w-full">
        <thead>
          <tr class="border-b border-gray-100 dark:border-gray-800">
            <th class="py-3 pl-4 text-left sm:pl-6"><p class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">#</p></th>
            <th class="py-3 text-left"><p class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Código</p></th>
            <th class="py-3 text-left"><p class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Descripción</p></th>
            <th class="py-3 text-left"><p class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Fec. vencimiento</p></th>
            <th v-if="variant === 'bajo-stock'" class="py-3 text-left"><p class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Stock</p></th>
            <th class="py-3 text-left"><p class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">P. venta</p></th>
            <th class="py-3 pr-4 text-left sm:pr-6"><p class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Estado</p></th>
          </tr>
        </thead>
        <tbody>
          <template v-if="items.length">
            <tr
              v-for="(p, i) in items"
              :key="(p.idproducto as number) || i"
              class="border-b border-gray-100 dark:border-gray-800"
            >
              <td class="py-3 pl-4 text-sm text-gray-800 dark:text-white/90 sm:pl-6">{{ i + 1 }}</td>
              <td class="py-3 text-sm text-gray-600 dark:text-gray-400">{{ p.codigo }}</td>
              <td class="py-3 text-sm text-gray-800 dark:text-white/90">{{ p.descripcion }}</td>
              <td class="py-3">
                <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-400">
                  {{ formatDate(p.fecha_vencimiento as string) }}
                </span>
              </td>
              <td v-if="variant === 'bajo-stock'" class="py-3">
                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                  {{ p.stock }}
                </span>
              </td>
              <td class="py-3 text-sm text-gray-600 dark:text-gray-400">
                {{ formatMoney(Number(p.precio_venta)) }}
              </td>
              <td class="py-3 pr-4 sm:pr-6">
                <span
                  :class="
                    String(p.estado) === '1'
                      ? 'rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400'
                      : 'rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-400'
                  "
                >
                  {{ estadoLabel(p.estado) }}
                </span>
              </td>
            </tr>
          </template>
          <tr v-else>
            <td :colspan="variant === 'bajo-stock' ? 7 : 6" class="py-8 text-center text-sm text-gray-500 dark:text-gray-400">
              {{ variant === 'por-vencer' ? 'No hay productos por vencer o vencidos.' : 'No hay productos con bajo stock.' }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
