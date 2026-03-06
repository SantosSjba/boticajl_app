<script setup lang="ts">
import { computed, inject, watch, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import IconMenu from '@/components/layout/IconMenu.vue';
import { ref } from 'vue';

export interface SidebarState {
  isExpanded: boolean;
  isMobileOpen: boolean;
  isHovered: boolean;
  toggleExpanded: () => void;
  toggleMobileOpen: () => void;
  setMobileOpen: (v: boolean) => void;
  setHovered: (v: boolean) => void;
}

const props = defineProps<{
  menuGroups: Array<{ title: string; items: Array<Record<string, unknown>> }>;
  currentPath: string;
}>();

// sidebar es ahora un reactive() object desde AppLayout (igual que $store.sidebar en Alpine)
const sidebar = inject<SidebarState>('sidebar')!;

const openSubmenus = ref<Record<string, boolean>>({});

function normPath(path: string): string {
  if (!path) return '';
  try {
    if (path.startsWith('http')) return new URL(path).pathname.replace(/^\//, '');
  } catch {
    // ignore
  }
  return path.replace(/^\//, '');
}

const currentNorm = computed(() => normPath(props.currentPath));

function isActive(path: string): boolean {
  return currentNorm.value === normPath(path);
}

function toggleSubmenu(groupIndex: number, itemIndex: number): void {
  const key = `${groupIndex}-${itemIndex}`;
  const next = !openSubmenus.value[key];
  openSubmenus.value = next ? { [key]: true } : { ...openSubmenus.value, [key]: false };
}

function isSubmenuOpen(groupIndex: number, itemIndex: number): boolean {
  return !!openSubmenus.value[`${groupIndex}-${itemIndex}`];
}

// sidebar es reactive, acceso directo sin .value
const showText = computed(() => sidebar.isExpanded || sidebar.isHovered || sidebar.isMobileOpen);

function initOpenSubmenus(): void {
  const next: Record<string, boolean> = {};
  props.menuGroups.forEach((group, gi) => {
    group.items.forEach((item, ii) => {
      const sub = item.subItems as Array<{ path: string }> | undefined;
      if (!sub) return;
      for (const s of sub) {
        const subPath = normPath(s.path);
        if (subPath && (currentNorm.value === subPath || currentNorm.value.startsWith(subPath + '/'))) {
          next[`${gi}-${ii}`] = true;
          return;
        }
      }
    });
  });
  openSubmenus.value = next;
}

watch(
  () => [props.currentPath, props.menuGroups],
  () => initOpenSubmenus(),
  { immediate: false },
);
onMounted(initOpenSubmenus);

function onMouseEnter(): void {
  if (typeof window !== 'undefined' && window.innerWidth >= 1280 && !sidebar.isExpanded) {
    sidebar.setHovered(true);
  }
}

function onMouseLeave(): void {
  sidebar.setHovered(false);
}
</script>

<template>
  <aside
    id="sidebar"
    class="fixed left-0 z-[99999] flex flex-col border-r border-gray-200 bg-white px-5 text-gray-900 transition-all duration-300 ease-in-out dark:border-gray-800 dark:bg-gray-900 top-16 h-[calc(100vh-4rem)] xl:top-0 xl:h-screen"
    :class="{
      'w-[290px]': sidebar.isExpanded || sidebar.isMobileOpen || sidebar.isHovered,
      'w-[90px]': !sidebar.isExpanded && !sidebar.isHovered,
      'translate-x-0': sidebar.isMobileOpen,
      '-translate-x-full xl:translate-x-0': !sidebar.isMobileOpen,
    }"
    @mouseenter="onMouseEnter"
    @mouseleave="onMouseLeave"
  >
    <!-- Logo: oculto en móvil (el header ya lo muestra al centro), visible en xl -->
    <div
      class="hidden pt-8 pb-7 xl:flex"
      :class="
        !sidebar.isExpanded && !sidebar.isHovered && !sidebar.isMobileOpen
          ? 'xl:justify-center'
          : 'justify-start'
      "
    >
      <Link href="/dashboard">
        <img
          v-show="showText"
          class="h-10 w-auto object-contain dark:invert-0"
          src="/images/logo/logo_oficial.png"
          alt="Botica J&amp;L"
        />
        <img
          v-show="!showText"
          class="size-8 object-contain"
          src="/images/logo/logo_oficial.png"
          alt="J&amp;L"
        />
      </Link>
    </div>

    <!-- Nav (en móvil sin logo, un poco de padding arriba) -->
    <div class="flex min-h-0 flex-1 flex-col overflow-y-auto pt-4 duration-300 ease-linear custom-scrollbar xl:pt-0">
      <nav class="mb-6">
        <div class="flex flex-col gap-4">
          <div v-for="(group, groupIndex) in menuGroups" :key="groupIndex">
            <h2
              class="mb-4 flex text-xs uppercase leading-5 text-gray-400"
              :class="
                !sidebar.isExpanded && !sidebar.isHovered && !sidebar.isMobileOpen
                  ? 'xl:justify-center'
                  : 'justify-start'
              "
            >
              <template v-if="showText">
                <span>{{ group.title }}</span>
              </template>
              <template v-else>
                <svg class="size-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                    fill="currentColor"
                  />
                </svg>
              </template>
            </h2>

            <ul class="flex flex-col gap-1">
              <li v-for="(item, itemIndex) in group.items" :key="itemIndex">
                <template v-if="item.subItems && Array.isArray(item.subItems)">
                  <button
                    type="button"
                    class="group relative flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium"
                    :class="[
                      isSubmenuOpen(groupIndex, itemIndex)
                        ? 'bg-brand-50 text-brand-500 dark:bg-brand-500/10 dark:text-brand-400'
                        : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5',
                      !sidebar.isExpanded && !sidebar.isHovered ? 'xl:justify-center' : 'xl:justify-start',
                    ]"
                    @click="toggleSubmenu(groupIndex, itemIndex)"
                  >
                    <span
                      :class="
                        isSubmenuOpen(groupIndex, itemIndex)
                          ? 'text-brand-500 dark:text-brand-400'
                          : 'text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-300'
                      "
                    >
                      <IconMenu :name="(item.icon as string) || 'dashboard'" />
                    </span>
                    <span v-show="showText" class="menu-item-text flex flex-1 items-center gap-2 text-sm">
                      {{ item.name }}
                    </span>
                    <svg
                      v-show="showText"
                      class="ml-auto size-5 shrink-0 transition-transform duration-200"
                      :class="{ 'rotate-180 text-brand-500': isSubmenuOpen(groupIndex, itemIndex) }"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                  <div
                    v-show="isSubmenuOpen(groupIndex, itemIndex) && showText"
                    class="mt-2 ml-9 space-y-1"
                  >
                    <ul>
                      <li
                        v-for="(subItem, subIdx) in (item.subItems as Array<{ name: string; path: string }>)"
                        :key="subIdx"
                      >
                        <Link
                          :href="subItem.path"
                          class="block rounded-lg px-3 py-2.5 text-sm font-medium"
                          :class="
                            isActive(subItem.path)
                              ? 'bg-brand-50 text-brand-500 dark:bg-brand-500/10 dark:text-brand-400'
                              : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5'
                          "
                        >
                          {{ subItem.name }}
                        </Link>
                      </li>
                    </ul>
                  </div>
                </template>
                <template v-else>
                  <Link
                    :href="(item.path as string) || '#'"
                    class="group relative flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium"
                    :class="[
                      isActive((item.path as string) || '')
                        ? 'bg-brand-50 text-brand-500 dark:bg-brand-500/10 dark:text-brand-400'
                        : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5',
                      !sidebar.isExpanded && !sidebar.isHovered && !sidebar.isMobileOpen
                        ? 'xl:justify-center'
                        : 'justify-start',
                    ]"
                  >
                    <span
                      :class="
                        isActive((item.path as string) || '')
                          ? 'text-brand-500 dark:text-brand-400'
                          : 'text-gray-500 dark:text-gray-400 dark:group-hover:text-gray-300'
                      "
                    >
                      <IconMenu :name="(item.icon as string) || 'dashboard'" />
                    </span>
                    <span v-show="showText" class="text-sm">{{ item.name }}</span>
                  </Link>
                </template>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <!-- Sidebar widget -->
      <div v-show="showText" class="mt-auto">
        <div
          class="mx-auto mb-10 w-full max-w-60 rounded-2xl bg-gray-50 px-4 py-5 text-center dark:bg-white/[0.03]"
        >
          <p class="text-theme-sm text-gray-600 dark:text-gray-400">
            Desarrollado por <strong class="text-gray-800 dark:text-white">FactosysPeru</strong>
          </p>
        </div>
      </div>
    </div>
  </aside>

  <!-- Mobile overlay -->
  <div
    v-show="sidebar.isMobileOpen"
    class="fixed inset-0 z-50 h-screen w-full bg-gray-900/50 xl:hidden"
    aria-hidden="true"
    @click="sidebar.setMobileOpen(false)"
  />
</template>
