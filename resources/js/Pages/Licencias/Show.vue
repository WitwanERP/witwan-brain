<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center gap-3">
        <Link :href="route('licencias.index')" class="text-gray-400 hover:text-gray-600">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
        </Link>
        {{ licencia.licencia_nombre }}
      </div>
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <!-- Info de licencia -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border p-6 sticky top-24">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Información</h3>

          <dl class="space-y-3 text-sm">
            <div>
              <dt class="text-gray-500">ID</dt>
              <dd class="font-medium font-mono">{{ licencia.licencia_id }}</dd>
            </div>
            <div>
              <dt class="text-gray-500">Nombre</dt>
              <dd class="font-medium">{{ licencia.licencia_nombre }}</dd>
            </div>
            <div>
              <dt class="text-gray-500">Key</dt>
              <dd class="font-mono text-xs bg-gray-100 px-2 py-1 rounded inline-block">{{ licencia.licencia_key }}</dd>
            </div>
            <div>
              <dt class="text-gray-500">País</dt>
              <dd class="font-medium">{{ licencia.licencia_pais || '-' }}</dd>
            </div>
            <div>
              <dt class="text-gray-500">Base de datos</dt>
              <dd class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ licencia.licencia_base }}</dd>
            </div>
            <div>
              <dt class="text-gray-500">Secciones asignadas</dt>
              <dd class="font-medium text-blue-600 text-lg">{{ localSelected.length }}</dd>
            </div>
          </dl>

          <div class="mt-6 pt-6 border-t space-y-2">
            <button
              @click="selectAll"
              class="w-full px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
            >
              Seleccionar todo
            </button>
            <button
              @click="deselectAll"
              class="w-full px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
            >
              Deseleccionar todo
            </button>
          </div>
        </div>
      </div>

      <!-- Asignación de secciones -->
      <div class="lg:col-span-3">
        <div class="bg-white rounded-xl shadow-sm border p-6">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Asignar Secciones</h3>

            <SistemaFilter
              :sistemas="sistemas"
              :model-value="sistemaActual"
              @change="filterBySistema"
            />
          </div>

          <div class="max-h-[60vh] overflow-y-auto">
            <SeccionTree
              :tree="arbol"
              :selected-ids="localSelected"
              :selectable="true"
              @toggle="toggleSeccion"
            />
          </div>

          <div class="mt-6 pt-6 border-t flex justify-between items-center">
            <p class="text-sm text-gray-500">
              {{ localSelected.length }} secciones seleccionadas
              <span v-if="hasChanges" class="text-amber-600 ml-2">(cambios sin guardar)</span>
            </p>
            <button
              @click="guardar"
              :disabled="saving || !hasChanges"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              {{ saving ? 'Guardando...' : 'Guardar cambios' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SeccionTree from '@/Components/SeccionTree.vue';
import SistemaFilter from '@/Components/SistemaFilter.vue';

const props = defineProps({
  licencia: Object,
  todasSecciones: Array,
  arbol: Array,
  seccionesAsignadas: Array,
  sistemas: Object,
  sistemaActual: [Number, null],
});

const localSelected = ref([...props.seccionesAsignadas]);
const saving = ref(false);
const originalSelected = [...props.seccionesAsignadas];

const hasChanges = computed(() => {
  if (localSelected.value.length !== originalSelected.length) return true;
  return !localSelected.value.every(id => originalSelected.includes(id));
});

const toggleSeccion = (id) => {
  const index = localSelected.value.indexOf(id);
  if (index === -1) {
    localSelected.value.push(id);
  } else {
    localSelected.value.splice(index, 1);
  }
};

const selectAll = () => {
  localSelected.value = props.todasSecciones.map(s => s.seccion_id);
};

const deselectAll = () => {
  localSelected.value = [];
};

const guardar = () => {
  saving.value = true;
  router.put(
    route('licencias.sync-secciones', props.licencia.licencia_id),
    { secciones: localSelected.value },
    {
      preserveScroll: true,
      onFinish: () => {
        saving.value = false;
      },
      onSuccess: () => {
        originalSelected.length = 0;
        originalSelected.push(...localSelected.value);
      }
    }
  );
};

const filterBySistema = (sistemaId) => {
  router.get(
    route('licencias.show', props.licencia.licencia_id),
    { sistema: sistemaId },
    { preserveState: true, preserveScroll: true }
  );
};
</script>
