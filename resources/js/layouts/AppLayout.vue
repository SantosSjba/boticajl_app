<script setup lang="ts">
import { reactive, computed, provide, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Sidebar from '@/components/layout/Sidebar.vue';
import AppHeader from '@/components/layout/AppHeader.vue';

defineSlots<{
  default?: () => unknown;
}>();

const page = usePage();

// Usar reactive() igual que Alpine $store.sidebar en el proyecto antiguo
// Así los valores se auto-desenvuelven en los templates sin necesidad de .value
const sidebar = reactive({
  isExpanded: typeof window !== 'undefined' ? window.innerWidth >= 1280 : true,
  isMobileOpen: false,
  isHovered: false,

  toggleExpanded() {
    this.isExpanded = !this.isExpanded;
    this.isMobileOpen = false;
  },

  toggleMobileOpen() {
    this.isMobileOpen = !this.isMobileOpen;
  },

  setMobileOpen(v: boolean) {
    this.isMobileOpen = v;
  },

  setHovered(v: boolean) {
    if (typeof window !== 'undefined' && window.innerWidth >= 1280 && !this.isExpanded) {
      this.isHovered = v;
    } else if (!v) {
      this.isHovered = false;
    }
  },
});

function onResize(): void {
  if (typeof window === 'undefined') return;
  if (window.innerWidth < 1280) {
    sidebar.isMobileOpen = false;
    sidebar.isExpanded = false;
  } else {
    sidebar.isMobileOpen = false;
    sidebar.isExpanded = true;
  }
}

onMounted(() => {
  if (typeof window !== 'undefined') {
    sidebar.isExpanded = window.innerWidth >= 1280;
    window.addEventListener('resize', onResize);
  }
});

onUnmounted(() => {
  if (typeof window !== 'undefined') window.removeEventListener('resize', onResize);
});

provide('sidebar', sidebar);

const menuGroups = computed(
  () => (page.props.menuGroups as Array<{ title: string; items: Array<Record<string, unknown>> }>) ?? [],
);
const currentPath = computed(() => (page.props.currentPath as string) ?? '');

const mainMarginClass = computed(() => {
  if (sidebar.isMobileOpen) return 'ml-0';
  const xlMargin = sidebar.isExpanded || sidebar.isHovered ? 'xl:ml-[290px]' : 'xl:ml-[90px]';
  return `ml-0 ${xlMargin}`;
});
</script>

<template>
  <div class="flex min-h-screen xl:flex">
    <Sidebar :menu-groups="menuGroups" :current-path="currentPath" />

    <div
      class="min-w-0 flex-1 transition-all duration-300 ease-in-out bg-gray-50 dark:bg-gray-900"
      :class="mainMarginClass"
    >
      <AppHeader />
      <main class="main-content-scroll mx-auto min-w-0 max-w-[1600px] overflow-x-hidden overflow-y-auto p-4 md:p-6">
        <slot />
      </main>
    </div>
  </div>
</template>
