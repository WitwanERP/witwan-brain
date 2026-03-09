<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center gap-3">
        <Link :href="route('secciones.index')" class="text-gray-400 hover:text-gray-600">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
        </Link>
        Nueva Sección
      </div>
    </template>

    <div class="max-w-3xl">
      <form @submit.prevent="submit" class="bg-white rounded-xl shadow-sm border p-6 space-y-6">
        <!-- Sistema -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Sistema</label>
          <div class="flex gap-2 flex-wrap">
            <button
              v-for="(nombre, id) in sistemas"
              :key="id"
              type="button"
              @click="changeSistema(Number(id))"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
              :class="form.fk_sistema_id === Number(id)
                ? 'bg-blue-600 text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
            >
              {{ nombre }}
            </button>
          </div>
        </div>

        <!-- Sección padre -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Sección Padre (opcional)</label>
          <select
            v-model="form.fk_seccion_id"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option :value="0">-- Ninguna (sección raíz) --</option>
            <option v-for="seccion in seccionesPadre" :key="seccion.seccion_id" :value="seccion.seccion_id">
              {{ seccion.seccion_grupo }} &gt; {{ seccion.seccion_nombre }}
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
              placeholder="Ej: Reservas"
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
              placeholder="Ej: Nueva Reserva"
            />
          </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
          <!-- Key -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Key (identificador único)</label>
            <input
              v-model="form.seccion_key"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono"
              placeholder="Ej: reservas.nueva"
            />
          </div>

          <!-- URI -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">URI</label>
            <input
              v-model="form.seccion_uri"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Ej: /reservas/nueva"
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
              placeholder="Ej: fa-calendar"
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
          <h3 class="text-sm font-medium text-gray-700 mb-4">Traducciones (opcional)</h3>

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
            :href="route('secciones.index')"
            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
          >
            Cancelar
          </Link>
          <button
            type="submit"
            :disabled="form.processing"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors"
          >
            {{ form.processing ? 'Guardando...' : 'Crear Sección' }}
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  sistemas: Object,
  seccionesPadre: Array,
  sistemaActual: Number,
  siguienteOrden: Number,
});

const seccionesPadre = ref(props.seccionesPadre);

const form = useForm({
  fk_seccion_id: 0,
  fk_sistema_id: props.sistemaActual || 1,
  seccion_key: '',
  seccion_grupo: '',
  seccion_grupo_en: '',
  seccion_grupo_pt: '',
  seccion_nombre: '',
  seccion_nombre_en: '',
  seccion_nombre_pt: '',
  seccion_uri: '',
  icono: '',
  orden: props.siguienteOrden || 0,
  ordenmt: 0,
});

const changeSistema = async (sistemaId) => {
  form.fk_sistema_id = sistemaId;
  form.fk_seccion_id = 0;

  // Fetch secciones padre del nuevo sistema
  const response = await fetch(route('secciones.por-sistema', { sistema: sistemaId }));
  const data = await response.json();
  seccionesPadre.value = data.seccionesPadre;
  form.orden = data.siguienteOrden;
};

const submit = () => {
  form.post(route('secciones.store'));
};
</script>
