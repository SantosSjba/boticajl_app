<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

defineProps<{
  title: string;
}>();
</script>

<template>
  <AppLayout>
    <Head :title="title" />
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
      <div class="mx-auto max-w-7xl">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">
          Inicio
        </h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">
          Bienvenido al sistema Botica J&amp;L.
        </p>
        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
          Utilice el menú lateral para acceder a los módulos. Los módulos en desarrollo mostrarán un aviso temporal.
        </p>
      </div>
    </div>
  </AppLayout>
</template>
