<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const dropdownOpen = ref(false);
const wrapperRef = ref<HTMLElement | null>(null);

const page = usePage();
const user = computed(() => page.props.auth?.user as { usuario?: string; nombres?: string; email?: string } | null);
const displayName = computed(() => user.value?.nombres ?? user.value?.usuario ?? 'Usuario');
const initial = computed(() => {
  const u = user.value?.usuario ?? user.value?.nombres ?? 'U';
  return String(u).charAt(0).toUpperCase();
});

const logoutUrl = computed(() => (page.props.logout_url as string) || '/logout');
const csrfToken = computed(() => (page.props.csrf_token as string) || '');

function toggle(): void {
  dropdownOpen.value = !dropdownOpen.value;
}

function close(): void {
  dropdownOpen.value = false;
}

function handleClickOutside(event: MouseEvent): void {
  if (!dropdownOpen.value) return;
  const el = wrapperRef.value;
  if (el && !el.contains(event.target as Node)) {
    close();
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});
onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
  <div ref="wrapperRef" class="relative">
    <button
      type="button"
      class="flex items-center text-gray-700 dark:text-gray-400"
      @click.prevent="toggle"
    >
      <span
        class="mr-3 flex size-11 items-center justify-center overflow-hidden rounded-full bg-brand-100 text-lg font-semibold text-brand-600 dark:bg-brand-900/30 dark:text-brand-400"
      >
        {{ initial }}
      </span>
      <span class="mr-1 block font-medium text-sm">{{ displayName }}</span>
      <svg
        class="size-5 shrink-0 transition-transform duration-200"
        :class="{ 'rotate-180': dropdownOpen }"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>

    <Transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        v-show="dropdownOpen"
        class="absolute right-0 top-full z-50 mt-[17px] flex w-[260px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-lg dark:border-gray-800 dark:bg-gray-900"
      >
      <div>
        <span class="block font-medium text-gray-700 text-sm dark:text-gray-400">{{ displayName }}</span>
        <span class="mt-0.5 block text-xs text-gray-500 dark:text-gray-400">{{ user?.email ?? user?.usuario }}</span>
      </div>

      <ul class="flex flex-col gap-1 border-b border-gray-200 py-3 pb-3 dark:border-gray-800">
        <li>
          <Link
            href="/profile"
            class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
            @click="close"
          >
            <svg class="size-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z"
                fill="currentColor"
              />
            </svg>
            Perfil
          </Link>
        </li>
      </ul>

      <form :action="logoutUrl" method="POST" class="mt-3">
        <input type="hidden" name="_token" :value="csrfToken">
        <button
          type="submit"
          class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
          @click="close"
        >
          <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
            />
          </svg>
          Cerrar sesión
        </button>
      </form>
      </div>
    </Transition>
  </div>
</template>
