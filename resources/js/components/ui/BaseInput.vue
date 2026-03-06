<script setup lang="ts">
defineProps<{
  id: string;
  modelValue: string;
  type?: 'text' | 'password' | 'email';
  label: string;
  placeholder?: string;
  error?: string;
  required?: boolean;
  autocomplete?: string;
  disabled?: boolean;
}>();

defineEmits<{
  'update:modelValue': [value: string];
}>();
</script>

<template>
  <div>
    <label
      :for="id"
      class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
    >
      {{ label }}<span v-if="required" class="text-red-500">*</span>
    </label>
    <input
      :id="id"
      :type="type ?? 'text'"
      :value="modelValue"
      :placeholder="placeholder"
      :required="required"
      :autocomplete="autocomplete"
      :disabled="disabled"
      :class="[
        'h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30',
        error
          ? 'border-red-500 dark:border-red-500'
          : 'border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500/10 dark:focus:border-brand-800',
      ]"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
    />
    <p v-if="error" class="mt-1 text-sm text-red-500 dark:text-red-400">
      {{ error }}
    </p>
  </div>
</template>
