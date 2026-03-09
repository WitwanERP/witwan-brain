<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center gap-3">
        <Link :href="route('licencias.index')" class="text-gray-400 hover:text-gray-600">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
        </Link>
        Editar Licencia
      </div>
    </template>

    <div class="max-w-4xl">
      <form @submit.prevent="submit" class="bg-white rounded-xl shadow-sm border p-6 space-y-6">
        <!-- Info -->
        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
          <div>
            <p class="text-sm text-gray-500">ID</p>
            <p class="font-mono font-medium">{{ licencia.licencia_id }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Key</p>
            <p class="font-mono font-medium">{{ licencia.licencia_key }}</p>
          </div>
          <div v-if="licencia.licencia_pais">
            <p class="text-sm text-gray-500">País</p>
            <p class="font-medium">{{ licencia.licencia_pais }}</p>
          </div>
        </div>

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
              />
            </div>

            <!-- Facturación -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Facturación</label>
              <input
                v-model="form.licencia_facturacion"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
              />
            </div>

            <!-- App URL -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">App URL</label>
              <input
                v-model="form.app_url"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
              />
            </div>

            <!-- User DB -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Usuario</label>
              <input
                v-model="form.user_db"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono"
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
              <p class="mt-1 text-xs text-gray-500">Dejar vacío para mantener la actual</p>
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
              />
            </div>

            <!-- Logo Voucher -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Logo Voucher</label>
              <input
                v-model="form.licencia_logo_voucher"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <!-- Logo -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Logo General</label>
              <input
                v-model="form.licencia_logo"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
        <div class="flex justify-between pt-6 border-t">
          <button
            type="button"
            @click="confirmDelete"
            class="px-6 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors"
          >
            Eliminar Licencia
          </button>

          <div class="flex gap-3">
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
              {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirmar Eliminación</h3>
        <p class="text-gray-600 mb-6">
          ¿Estás seguro de que deseas eliminar la licencia <strong>{{ licencia.licencia_nombre }}</strong>?
          Esta acción no se puede deshacer y se eliminarán todas las secciones asignadas.
        </p>
        <div class="flex justify-end gap-3">
          <button
            @click="showDeleteModal = false"
            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
          >
            Cancelar
          </button>
          <button
            @click="deleteLicencia"
            :disabled="deleting"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 transition-colors"
          >
            {{ deleting ? 'Eliminando...' : 'Eliminar' }}
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  licencia: Object,
  licenciasPadre: Array,
});

const showDeleteModal = ref(false);
const deleting = ref(false);

const form = useForm({
  licencia_nombre: props.licencia.licencia_nombre || '',
  licencia_facturacion: props.licencia.licencia_facturacion || '',
  licencia_key: props.licencia.licencia_key || '',
  licencia_base: props.licencia.licencia_base || '',
  licencia_logo_login: props.licencia.licencia_logo_login || '',
  licencia_logo_voucher: props.licencia.licencia_logo_voucher || '',
  licencia_logo: props.licencia.licencia_logo || '',
  licencia_url: props.licencia.licencia_url || '',
  licencia_pais: props.licencia.licencia_pais || '',
  fk_licencia_id: props.licencia.fk_licencia_id || null,
  base_colectora: props.licencia.base_colectora || '',
  activar_cron: Boolean(props.licencia.activar_cron),
  prioridad: props.licencia.prioridad || 0,
  host_db: props.licencia.host_db || '',
  user_db: props.licencia.user_db || '',
  pass_db: '',
  app_url: props.licencia.app_url || '',
});

const submit = () => {
  form.put(route('licencias.update', props.licencia.licencia_id));
};

const confirmDelete = () => {
  showDeleteModal.value = true;
};

const deleteLicencia = () => {
  deleting.value = true;
  router.delete(route('licencias.destroy', props.licencia.licencia_id), {
    onFinish: () => {
      deleting.value = false;
      showDeleteModal.value = false;
    },
  });
};
</script>
