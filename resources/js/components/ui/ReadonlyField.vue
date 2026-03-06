<script setup lang="ts">
defineProps<{
  id: string;
  label: string;
  modelValue: string;
}>();
</script>

<template>
  <div>
    <label
      :for="id"
      class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
    >
      {{ label }}
    </label>
    <input
      :id="id"
      type="text"
      :value="modelValue"
      readonly
      class="h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-800 shadow-sm dark:border-gray-700 dark:bg-gray-800/50 dark:text-white/90 dark:placeholder:text-white/30"
    />
  </div>
</template>
