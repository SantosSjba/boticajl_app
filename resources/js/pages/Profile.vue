<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Card from '@/components/ui/Card.vue';
import ReadonlyField from '@/components/ui/ReadonlyField.vue';

defineProps<{
  title: string;
}>();

const page = usePage();
const user = (page.props.auth?.user ?? null) as {
  usuario?: string;
  nombres?: string;
  email?: string;
  telefono?: string;
  cargo_usu?: string;
  tipo?: string;
  fechaingreso?: string;
  estado?: string;
} | null;

function formatDate(value: string | null | undefined): string {
  if (!value) return '—';
  try {
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return '—';
    return d.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' });
  } catch {
    return '—';
  }
}

function str(value: string | null | undefined): string {
  return value ?? '—';
}
</script>

<template>
  <AppLayout>
    <Head :title="title" />
    <div class="min-w-0 space-y-6">
      <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-800 dark:text-white">
          {{ title }}
        </h1>
      </div>

      <Card
        v-if="user"
        title="Información del usuario"
        desc="Datos de tu cuenta en el sistema."
      >
        <div class="space-y-6">
          <div class="grid gap-4 sm:grid-cols-2">
            <ReadonlyField id="profile-usuario" label="Usuario" :model-value="str(user.usuario)" />
            <ReadonlyField id="profile-nombres" label="Nombres" :model-value="str(user.nombres)" />
          </div>
          <div class="grid gap-4 sm:grid-cols-2">
            <ReadonlyField id="profile-email" label="Correo electrónico" :model-value="str(user.email)" />
            <ReadonlyField id="profile-telefono" label="Teléfono" :model-value="str(user.telefono)" />
          </div>
          <div class="grid gap-4 sm:grid-cols-2">
            <ReadonlyField id="profile-cargo" label="Cargo" :model-value="str(user.cargo_usu)" />
            <ReadonlyField id="profile-tipo" label="Tipo" :model-value="str(user.tipo)" />
          </div>
          <div class="grid gap-4 sm:grid-cols-2">
            <ReadonlyField id="profile-fecha" label="Fecha de ingreso" :model-value="formatDate(user.fechaingreso)" />
            <ReadonlyField id="profile-estado" label="Estado" :model-value="str(user.estado)" />
          </div>
        </div>
      </Card>

      <Card v-else title="Perfil" desc="No hay sesión activa.">
        <p class="text-sm text-gray-600 dark:text-gray-400">
          Inicia sesión para ver tu perfil.
        </p>
      </Card>
    </div>
  </AppLayout>
</template>
