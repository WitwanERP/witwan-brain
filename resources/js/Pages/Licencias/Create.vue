<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center gap-3">
        <Link :href="route('licencias.index')" class="text-gray-400 hover:text-gray-600">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
        </Link>
        Nueva Licencia
      </div>
    </template>

    <div class="max-w-4xl">
      <form @submit.prevent="submit" class="bg-white rounded-xl shadow-sm border p-6 space-y-6">
        <!-- Información básica -->
        <div class="space-y-4">
          <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Información Básica</h3>

          <div class="grid grid-cols-2 gap-6">
            <!-- Nombre -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
              <input
                v-model="form.licencia_nombre"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': form.errors.licencia_nombre }"
                placeholder="Ej: Mi Empresa"
              />
              <p v-if="form.errors.licencia_nombre" class="mt-1 text-sm text-red-500">{{ form.errors.licencia_nombre }}</p>
            </div>

            <!-- Key -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Key (identificador único) *</label>
              <input
                v-model="form.licencia_key"
                type="text"
                required
                maxlength="30"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono uppercase"
                :class="{ 'border-red-500': form.errors.licencia_key }"
                placeholder="Ej: MIEMPRESA"
              />
              <p v-if="form.errors.licencia_key" class="mt-1 text-sm text-red-500">{{ form.errors.licencia_key }}</p>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-6">
            <!-- País -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">País</label>
              <input
                v-model="form.licencia_pais"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Ej: Argentina"
              />
            </div>

            <!-- Facturación -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Facturación</label>
              <input
                v-model="form.licencia_facturacion"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Ej: Razón Social"
              />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-6">
            <!-- URL -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">URL</label>
              <input
                v-model="form.licencia_url"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Ej: https://miempresa.com"
              />
            </div>

            <!-- App URL -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">App URL</label>
              <input
                v-model="form.app_url"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Ej: https://app.miempresa.com"
              />
            </div>
          </div>

          <!-- Licencia Padre -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Licencia Padre (opcional)</label>
            <select
              v-model="form.fk_licencia_id"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option :value="null">-- Ninguna --</option>
              <option v-for="lic in licenciasPadre" :key="lic.licencia_id" :value="lic.licencia_id">
                {{ lic.licencia_nombre }}
              </option>
            </select>
          </div>
        </div>

        <!-- Base de Datos -->
        <div class="space-y-4">
          <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Base de Datos</h3>

          <div class="grid grid-cols-2 gap-6">
            <!-- Base -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Base *</label>
              <input
                v-model="form.licencia_base"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono"
                :class="{ 'border-red-500': form.errors.licencia_base }"
                placeholder="Ej: witwan_miempresa"
              />
              <p v-if="form.errors.licencia_base" class="mt-1 text-sm text-red-500">{{ form.errors.licencia_base }}</p>
            </div>

            <!-- Base Colectora -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Base Colectora</label>
              <input
                v-model="form.base_colectora"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono"
                placeholder="Ej: witwan_colectora"
              />
            </div>
          </div>

          <div class="grid grid-cols-3 gap-6">
            <!-- Host DB -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Host</label>
              <input
                v-model="form.host_db"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono"
                placeholder="Ej: localhost"
              />
            </div>

            <!-- User DB -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Usuario</label>
              <input
                v-model="form.user_db"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono"
                placeholder="Ej: root"
              />
            </div>

            <!-- Pass DB -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
              <input
                v-model="form.pass_db"
                type="password"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono"
                placeholder="••••••••"
              />
            </div>
          </div>
        </div>

        <!-- Logos -->
        <div class="space-y-4">
          <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Logos</h3>

          <div class="grid grid-cols-3 gap-6">
            <!-- Logo Login -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Logo Login</label>
              <input
                v-model="form.licencia_logo_login"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Ej: logo_login.png"
              />
            </div>

            <!-- Logo Voucher -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Logo Voucher</label>
              <input
                v-model="form.licencia_logo_voucher"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Ej: logo_voucher.png"
              />
            </div>

            <!-- Logo -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Logo General</label>
              <input
                v-model="form.licencia_logo"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Ej: logo.png"
              />
            </div>
          </div>
        </div>

        <!-- Configuración -->
        <div class="space-y-4">
          <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Configuración</h3>

          <div class="grid grid-cols-2 gap-6">
            <!-- Prioridad -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Prioridad</label>
              <input
                v-model.number="form.prioridad"
                type="number"
                min="0"
                max="9"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <!-- Activar Cron -->
            <div class="flex items-center pt-8">
              <label class="flex items-center gap-3 cursor-pointer">
                <input
                  v-model="form.activar_cron"
                  type="checkbox"
                  class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                />
                <span class="text-sm font-medium text-gray-700">Activar Cron</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Botones -->
        <div class="flex justify-end gap-3 pt-6 border-t">
          <Link
            :href="route('licencias.index')"
            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
          >
            Cancelar
          </Link>
          <button
            type="submit"
            :disabled="form.processing"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors"
          >
            {{ form.processing ? 'Creando...' : 'Crear Licencia' }}
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  licenciasPadre: Array,
});

const form = useForm({
  licencia_nombre: '',
  licencia_facturacion: '',
  licencia_key: '',
  licencia_base: '',
  licencia_logo_login: '',
  licencia_logo_voucher: '',
  licencia_logo: '',
  licencia_url: '',
  licencia_pais: '',
  fk_licencia_id: null,
  base_colectora: '',
  activar_cron: false,
  prioridad: 0,
  host_db: '',
  user_db: '',
  pass_db: '',
  app_url: '',
});

const submit = () => {
  form.post(route('licencias.store'));
};
</script>
