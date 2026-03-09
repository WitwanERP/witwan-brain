<template>
  <AuthenticatedLayout>
    <template #header>Gestión de Secciones</template>

    <div class="space-y-6">
      <!-- Filtros y acciones -->
      <div class="flex items-center justify-between flex-wrap gap-4">
        <SistemaFilter
          :sistemas="sistemas"
          :model-value="sistemaActual"
          @change="filterBySistema"
        />

        <Link
          :href="route('secciones.create', { sistema: sistemaActual || 1 })"
          class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Nueva Sección
        </Link>
      </div>

      <!-- Árbol de secciones -->
      <div class="bg-white rounded-xl shadow-sm border p-6">
        <SeccionTree
          :tree="arbol"
          :editable="true"
        />
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SeccionTree from '@/Components/SeccionTree.vue';
import SistemaFilter from '@/Components/SistemaFilter.vue';

const props = defineProps({
  secciones: Array,
  arbol: Array,
  sistemas: Object,
  sistemaActual: [Number, null],
});

const filterBySistema = (sistemaId) => {
  router.get(route('secciones.index'), { sistema: sistemaId }, {
    preserveState: true,
    preserveScroll: true,
  });
};
</script>
