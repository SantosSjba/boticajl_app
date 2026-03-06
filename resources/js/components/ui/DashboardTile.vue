<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
  href: string;
  iconColor?: 'cyan' | 'red' | 'blue' | 'amber';
  label: string;
  value?: string;
  subtext?: string;
}>();

const iconPaths: Record<string, string> = {
  cyan: 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
  red: 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
  blue: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
  amber: 'M20 7l-8 4-8-4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
};
</script>

<template>
  <Link
    :href="href"
    class="rounded-2xl border border-gray-200 bg-white p-5 transition hover:border-brand-200 hover:shadow-sm dark:border-gray-800 dark:bg-white/[0.03] dark:hover:border-brand-800 md:p-6"
  >
    <div
      class="flex h-12 w-12 items-center justify-center rounded-xl"
      :class="{
        'bg-cyan-100 dark:bg-cyan-900/30': (iconColor || 'cyan') === 'cyan',
        'bg-red-100 dark:bg-red-900/30': iconColor === 'red',
        'bg-blue-100 dark:bg-blue-900/30': iconColor === 'blue',
        'bg-amber-100 dark:bg-amber-900/30': iconColor === 'amber',
      }"
    >
      <svg
        class="h-6 w-6"
        :class="{
          'text-cyan-600 dark:text-cyan-400': (iconColor || 'cyan') === 'cyan',
          'text-red-600 dark:text-red-400': iconColor === 'red',
          'text-blue-600 dark:text-blue-400': iconColor === 'blue',
          'text-amber-600 dark:text-amber-400': iconColor === 'amber',
        }"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="iconPaths[iconColor || 'cyan']" />
      </svg>
    </div>
    <p class="mt-4 text-sm font-medium text-gray-500 dark:text-gray-400">{{ label }}</p>
    <p v-if="value" class="mt-1 text-lg font-bold text-gray-800 dark:text-white/90">{{ value }}</p>
    <p v-else-if="subtext" class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ subtext }}</p>
  </Link>
</template>
