<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import BaseButton from '@/components/ui/BaseButton.vue';
import BaseInput from '@/components/ui/BaseInput.vue';
import BasePasswordInput from '@/components/ui/BasePasswordInput.vue';

const props = defineProps<{
  title: string;
  loginUrl: string;
  dashboardUrl: string;
}>();

const form = useForm({
  usuario: '',
  clave: '',
});

function submit() {
  form.post(props.loginUrl, {
    preserveScroll: true,
    onFinish: () => form.reset('clave'),
  });
}

function errorMessage(field: string): string {
  const v = form.errors[field as keyof typeof form.errors];
  return Array.isArray(v) ? v[0] : (v as string) ?? '';
}

function toggleTheme() {
  const html = document.documentElement;
  const isDark = html.classList.toggle('dark');
  if (isDark) {
    document.body.classList.add('dark', 'bg-gray-900');
  } else {
    document.body.classList.remove('dark', 'bg-gray-900');
  }
  try {
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
  } catch (_) {}
}
</script>

<template>
  <Head :title="title" />
  <div class="relative z-[1] bg-white dark:bg-gray-900">
    <div
      class="relative flex h-screen w-full flex-col justify-center dark:bg-gray-900 lg:flex-row"
    >
      <!-- Columna formulario -->
      <div class="flex w-full flex-1 flex-col lg:w-1/2">
        <div class="mx-auto w-full max-w-md pt-10">
          <a
            :href="props.loginUrl"
            class="inline-flex items-center text-sm text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
          >
            <img
              src="/images/logo/logo_oficial.png"
              alt="Botica J&amp;L"
              class="h-10 w-auto object-contain"
            />
          </a>
        </div>
        <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center px-6">
          <div>
            <div class="mb-5 sm:mb-8">
              <h1
                class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90 sm:text-3xl"
              >
                Iniciar sesión
              </h1>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Ingresa tu usuario y contraseña para acceder al sistema.
              </p>
            </div>
            <div v-if="Object.keys(form.errors).length > 0" class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
              <p v-for="(msg, key) in form.errors" :key="String(key)">
                {{ Array.isArray(msg) ? msg[0] : msg }}
              </p>
            </div>
            <form class="space-y-5" @submit.prevent="submit">
              <BaseInput
                id="usuario"
                v-model="form.usuario"
                label="Usuario"
                placeholder="Usuario"
                autocomplete="username"
                required
                :error="errorMessage('usuario')"
              />
              <BasePasswordInput
                id="clave"
                v-model="form.clave"
                label="Contraseña"
                placeholder="Contraseña"
                required
                :error="errorMessage('clave')"
              />
              <BaseButton
                type="submit"
                label="Iniciar sesión"
                :loading="form.processing"
              />
            </form>
          </div>
        </div>
      </div>

      <!-- Columna derecha (panel brand) -->
      <div
        class="relative hidden h-full w-full items-center bg-brand-950 dark:bg-white/5 lg:grid lg:w-1/2"
      >
        <div class="z-[1] flex items-center justify-center">
          <div class="flex max-w-xs flex-col items-center">
            <a :href="props.loginUrl" class="mb-4 block">
              <img
                src="/images/logo/logo_oficial.png"
                alt="Botica J&amp;L"
                class="h-24 w-auto object-contain drop-shadow-md"
              />
            </a>
            <p class="text-center text-gray-400 dark:text-white/60">
              Sistema de Farmacia / Botica J&amp;L
            </p>
          </div>
        </div>
      </div>

      <!-- Toggle tema -->
      <div class="fixed right-6 bottom-6 z-50">
        <button
          type="button"
          class="inline-flex size-14 items-center justify-center rounded-full bg-brand-500 text-white transition-colors hover:bg-brand-600"
          aria-label="Alternar tema claro/oscuro"
          @click="toggleTheme"
        >
          <svg class="h-5 w-5 dark:hidden" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.4547 11.97L18.1799 12.1611C18.265 11.8383 18.1265 11.4982 17.8401 11.3266C17.5538 11.1551 17.1885 11.1934 16.944 11.4207L17.4547 11.97ZM8.0306 2.5459L8.57989 3.05657C8.80718 2.81209 8.84554 2.44682 8.67398 2.16046C8.50243 1.8741 8.16227 1.73559 7.83948 1.82066L8.0306 2.5459ZM12.9154 13.0035C9.64678 13.0035 6.99707 10.3538 6.99707 7.08524H5.49707C5.49707 11.1823 8.81835 14.5035 12.9154 14.5035V13.0035ZM16.944 11.4207C15.8869 12.4035 14.4721 13.0035 12.9154 13.0035V14.5035C14.8657 14.5035 16.6418 13.7499 17.9654 12.5193L16.944 11.4207Z" />
          </svg>
          <svg class="hidden h-5 w-5 dark:block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.99998 1.5415C10.4142 1.5415 10.75 1.87729 10.75 2.2915V3.5415C10.75 3.95572 10.4142 4.2915 9.99998 4.2915C9.58577 4.2915 9.24998 3.95572 9.24998 3.5415V2.2915C9.24998 1.87729 9.58577 1.5415 9.99998 1.5415ZM10.0009 6.79327C8.22978 6.79327 6.79402 8.22904 6.79402 10.0001C6.79402 11.7712 8.22978 13.207 10.0009 13.207C11.772 13.207 13.2078 11.7712 13.2078 10.0001C13.2078 8.22904 11.772 6.79327 10.0009 6.79327Z" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>
