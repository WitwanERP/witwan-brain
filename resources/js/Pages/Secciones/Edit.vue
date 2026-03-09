<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center gap-3">
        <Link :href="route('secciones.index', { sistema: seccion.fk_sistema_id })" class="text-gray-400 hover:text-gray-600">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
        </Link>
        Editar Sección
      </div>
    </template>

    <div class="max-w-3xl">
      <form @submit.prevent="submit" class="bg-white rounded-xl shadow-sm border p-6 space-y-6">
        <!-- Info -->
        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
          <div>
            <p class="text-sm text-gray-500">ID</p>
            <p class="font-mono font-medium">{{ seccion.seccion_id }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Sistema</p>
            <p class="font-medium">{{ sistemas[seccion.fk_sistema_id] }}</p>
          </div>
          <div v-if="seccion.hijos?.length">
            <p class="text-sm text-gray-500">Subsecciones</p>
            <p class="font-medium">{{ seccion.hijos.length }}</p>
          </div>
        </div>

        <!-- Sección padre -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Sección Padre</label>
          <select
            v-model="form.fk_seccion_id"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option :value="0">-- Ninguna (sección raíz) --</option>
            <option v-for="s in seccionesPadre" :key="s.seccion_id" :value="s.seccion_id">
              {{ s.seccion_grupo }} &gt; {{ s.seccion_nombre }}
            </option>
          </select>
        </div>

        <div class="grid grid-cols-2 gap-6">
          <!-- Grupo -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Grupo *</label>
            <input
              v-model="form.seccion_grupo"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>

          <!-- Nombre -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
            <input
              v-model="form.seccion_nombre"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
          <!-- Key -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Key</label>
            <input
              v-model="form.seccion_key"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono"
            />
          </div>

          <!-- URI -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">URI</label>
            <input
              v-model="form.seccion_uri"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
          <!-- Icono -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Icono</label>
            <input
              v-model="form.icono"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>

          <!-- Orden -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Orden *</label>
            <input
              v-model.number="form.orden"
              type="number"
              required
              min="0"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        </div>

        <!-- Traducciones -->
        <div class="border-t pt-6">
          <h3 class="text-sm font-medium text-gray-700 mb-4">Traducciones</h3>

          <div class="grid grid-cols-2 gap-6">
            <div>
              <label class="block text-sm text-gray-500 mb-1">Grupo (Inglés)</label>
              <input
                v-model="form.seccion_grupo_en"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
              />
            </div>
            <div>
              <label class="block text-sm text-gray-500 mb-1">Nombre (Inglés)</label>
              <input
                v-model="form.seccion_nombre_en"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
              />
            </div>
            <div>
              <label class="block text-sm text-gray-500 mb-1">Grupo (Portugués)</label>
              <input
                v-model="form.seccion_grupo_pt"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
              />
            </div>
            <div>
              <label class="block text-sm text-gray-500 mb-1">Nombre (Portugués)</label>
              <input
                v-model="form.seccion_nombre_pt"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
              />
            </div>
          </div>
        </div>

        <!-- Botones -->
        <div class="flex justify-end gap-3 pt-6 border-t">
          <Link
            :href="route('secciones.index', { sistema: seccion.fk_sistema_id })"
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
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  seccion: Object,
  sistemas: Object,
  seccionesPadre: Array,
});

const form = useForm({
  fk_seccion_id: props.seccion.fk_seccion_id || 0,
  fk_sistema_id: props.seccion.fk_sistema_id,
  seccion_key: props.seccion.seccion_key || '',
  seccion_grupo: props.seccion.seccion_grupo || '',
  seccion_grupo_en: props.seccion.seccion_grupo_en || '',
  seccion_grupo_pt: props.seccion.seccion_grupo_pt || '',
  seccion_nombre: props.seccion.seccion_nombre || '',
  seccion_nombre_en: props.seccion.seccion_nombre_en || '',
  seccion_nombre_pt: props.seccion.seccion_nombre_pt || '',
  seccion_uri: props.seccion.seccion_uri || '',
  icono: props.seccion.icono || '',
  orden: props.seccion.orden || 0,
  ordenmt: props.seccion.ordenmt || 0,
});

const submit = () => {
  form.put(route('secciones.update', props.seccion.seccion_id));
};
</script>
