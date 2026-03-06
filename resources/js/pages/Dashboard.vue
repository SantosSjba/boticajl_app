<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';

defineProps<{
  title: string;
}>();
</script>

<template>
  <Head :title="title" />
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-6">
    <div class="mx-auto max-w-7xl">
      <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">
        Dashboard
      </h1>
      <p class="mt-2 text-gray-600 dark:text-gray-400">
        Bienvenido al sistema Botica J&amp;L.
      </p>
      <form
        class="mt-6"
        @submit.prevent="router.post('/logout')"
      >
        <button
          type="submit"
          class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600"
        >
          Cerrar sesión
        </button>
      </form>
    </div>
  </div>
</template>
