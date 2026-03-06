<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import DashboardWelcome from '@/components/dashboard/DashboardWelcome.vue';
import DashboardTile from '@/components/ui/DashboardTile.vue';
import DashboardResumenFinanciero from '@/components/dashboard/DashboardResumenFinanciero.vue';
import DashboardProductosTable from '@/components/dashboard/DashboardProductosTable.vue';

const props = withDefaults(
  defineProps<{
    title: string;
    fechaTexto: string;
    usuario: string;
    razonSocial?: string;
    montoCaja: number;
    simboloMoneda: string;
    isAdministrador: boolean;
    showComprasTile: boolean;
    productosPorVencer: Array<Record<string, unknown>>;
    productosBajoStock: Array<Record<string, unknown>>;
    fechaDesde?: string;
    fechaHasta?: string;
    filtroUsuario?: number;
    listaUsuarios?: Array<{ idusu: number; usuario: string; nombres: string | null }>;
    ventas?: number;
    costos?: number;
    ganancia?: number;
    gastos?: number;
    neto?: number;
  }>(),
  {
    razonSocial: '',
    productosPorVencer: () => [],
    productosBajoStock: () => [],
    ventas: 0,
    costos: 0,
    ganancia: 0,
    gastos: 0,
    neto: 0,
  }
);

function formatCaja(): string {
  return `${props.simboloMoneda} ${props.montoCaja.toLocaleString('es-PE', { minimumFractionDigits: 2 })}`;
}
</script>

<template>
  <AppLayout>
    <Head :title="title" />
    <div class="space-y-6">
      <DashboardWelcome
        :fecha-texto="fechaTexto"
        :usuario="usuario"
        :razon-social="razonSocial"
      />

      <!-- Tiles: Caja, Compras (solo admin), Clientes, Productos -->
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <DashboardTile
          href="/caja/apertura"
          icon-color="cyan"
          label="Caja"
          :value="`Apertura: ${formatCaja()}`"
        />
        <DashboardTile
          v-if="showComprasTile"
          href="/compras/consulta"
          icon-color="red"
          label="Compras"
          subtext="Consulta compras"
        />
        <DashboardTile
          href="/mantenimiento/clientes"
          icon-color="blue"
          label="Clientes"
          subtext="Ir a clientes"
        />
        <DashboardTile
          href="/mantenimiento/productos"
          icon-color="amber"
          label="Productos"
          subtext="Ir a productos"
        />
      </div>

      <!-- Resumen Financiero: solo ADMINISTRADOR -->
      <DashboardResumenFinanciero
        v-if="isAdministrador && fechaDesde != null && fechaHasta != null && listaUsuarios != null"
        :fecha-desde="fechaDesde"
        :fecha-hasta="fechaHasta"
        :filtro-usuario="filtroUsuario ?? 0"
        :lista-usuarios="listaUsuarios"
        :ventas="ventas ?? 0"
        :costos="costos ?? 0"
        :ganancia="ganancia ?? 0"
        :gastos="gastos ?? 0"
        :neto="neto ?? 0"
        :simbolo-moneda="simboloMoneda"
      />

      <!-- Productos por vencer -->
      <DashboardProductosTable
        title="Productos por vencer (14 días) o vencidos"
        :items="productosPorVencer"
        :simbolo-moneda="simboloMoneda"
        variant="por-vencer"
      />

      <!-- Productos bajo stock -->
      <DashboardProductosTable
        title="Productos con bajo stock"
        :items="productosBajoStock"
        :simbolo-moneda="simboloMoneda"
        variant="bajo-stock"
      />
    </div>
  </AppLayout>
</template>
