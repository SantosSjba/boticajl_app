<script setup lang="ts">
defineProps<{
  fechaTexto: string;
  usuario: string;
  razonSocial?: string;
}>();
</script>

<template>
  <div
    class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6"
  >
    <h1 class="text-xl font-semibold text-gray-800 dark:text-white/90">
      {{ fechaTexto }}
    </h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
      Bienvenido <strong>{{ usuario }}</strong>
      <template v-if="razonSocial">
        · Razón social: <strong>{{ razonSocial }}</strong>
      </template>
    </p>
  </div>
</template>
