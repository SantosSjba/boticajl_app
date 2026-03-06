<script setup lang="ts">
defineProps<{
  title: string;
  desc?: string;
}>();

defineSlots<{
  default?: () => unknown;
}>();
</script>

<template>
  <div
    class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900 lg:p-6"
  >
    <div class="mb-6">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
        {{ title }}
      </h3>
      <p
        v-if="desc"
        class="mt-1 text-sm text-gray-500 dark:text-gray-400"
      >
        {{ desc }}
      </p>
    </div>
    <slot />
  </div>
</template>
