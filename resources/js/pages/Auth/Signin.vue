<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import BaseButton from '@/components/ui/BaseButton.vue';
import BaseInput from '@/components/ui/BaseInput.vue';
import BasePasswordInput from '@/components/ui/BasePasswordInput.vue';
import IconSun from '@/components/ui/IconSun.vue';
import IconMoon from '@/components/ui/IconMoon.vue';

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
        <div class="z-[1] flex items-center justify-center p-8">
          <div class="flex max-w-sm flex-col items-center">
            <a :href="props.loginUrl" class="mb-5 block focus:outline-none focus:ring-2 focus:ring-white/30 focus:ring-offset-2 focus:ring-offset-brand-950 rounded-lg">
              <img
                src="/images/logo/logo_oficial.png"
                alt="Botica J&amp;L"
                class="h-28 w-auto max-h-44 object-contain drop-shadow-lg sm:h-32 lg:h-36"
              />
            </a>
            <p class="text-center text-sm text-gray-400 dark:text-white/60 sm:text-base">
              Sistema de Farmacia / Botica J&amp;L
            </p>
          </div>
        </div>
      </div>

      <!-- Botón modo oscuro (igual que proyecto antiguo) -->
      <div class="fixed right-6 bottom-6 z-50">
        <button
          type="button"
          class="bg-brand-500 hover:bg-brand-600 inline-flex size-14 items-center justify-center rounded-full text-white transition-colors"
          aria-label="Alternar tema claro/oscuro"
          @click="toggleTheme"
        >
          <!-- Sol: se muestra en modo oscuro (para cambiar a claro) -->
          <IconSun class="hidden dark:block" />
          <!-- Luna: se muestra en modo claro (para cambiar a oscuro) -->
          <IconMoon class="dark:hidden" />
        </button>
      </div>
    </div>
  </div>
</template>
