<script setup lang="ts">
const props = withDefaults(
  defineProps<{
    type?: 'button' | 'submit';
    label: string;
    loading?: boolean;
    loadingText?: string;
    disabled?: boolean;
    class?: string;
  }>(),
  { type: 'button', loadingText: 'Cargando...' }
);
</script>

<template>
  <button
    :type="props.type"
    :disabled="props.disabled ?? props.loading"
    :class="[
      'w-full rounded-lg px-4 py-3 text-sm font-medium text-white transition disabled:cursor-not-allowed disabled:opacity-70',
      props.class ?? 'bg-brand-500 hover:bg-brand-600 shadow-sm',
    ]"
  >
    <span v-if="props.loading">{{ props.loadingText }}</span>
    <span v-else>{{ props.label }}</span>
  </button>
</template>
