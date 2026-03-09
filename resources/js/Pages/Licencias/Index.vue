<template>
  <AuthenticatedLayout>
    <template #header>Gestión de Licencias</template>

    <div class="space-y-6">
      <!-- Búsqueda y acciones -->
      <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-4 flex-1">
          <div class="relative flex-1 max-w-md">
            <input
              v-model="search"
              type="text"
              placeholder="Buscar licencia..."
              class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              @input="debouncedSearch"
            />
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <span class="text-sm text-gray-500">
            {{ licencias.total }} licencias
          </span>
        </div>
        <Link
          :href="route('licencias.create')"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Nueva Licencia
        </Link>
      </div>

      <!-- Lista de licencias -->
      <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full">
          <thead class="bg-gray-50 border-b">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">País</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Base</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Secciones</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="licencia in licencias.data" :key="licencia.licencia_id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">
                {{ licencia.licencia_id }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="font-medium text-gray-900">{{ licencia.licencia_nombre }}</div>
                <div class="text-sm text-gray-500">{{ licencia.licencia_key }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ licencia.licencia_pais || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">
                {{ licencia.licencia_base }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                  {{ licencia.secciones_count }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                <div class="flex items-center justify-end gap-3">
                  <Link
                    :href="route('licencias.show', licencia.licencia_id)"
                    class="text-blue-600 hover:text-blue-800 font-medium"
                  >
                    Secciones
                  </Link>
                  <Link
                    :href="route('licencias.edit', licencia.licencia_id)"
                    class="text-gray-600 hover:text-gray-800"
                    title="Editar"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </Link>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <div v-if="licencias.last_page > 1" class="flex items-center justify-center gap-2">
        <Link
          v-for="link in licencias.links"
          :key="link.label"
          :href="link.url"
          class="px-4 py-2 text-sm rounded-lg transition-colors"
          :class="link.active
            ? 'bg-blue-600 text-white'
            : link.url
              ? 'bg-gray-100 text-gray-700 hover:bg-gray-200'
              : 'bg-gray-50 text-gray-400 cursor-not-allowed'"
          v-html="link.label"
        />
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  licencias: Object,
  filters: Object,
});

const search = ref(props.filters.search || '');

let searchTimeout = null;
const debouncedSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    router.get(route('licencias.index'), { search: search.value }, {
      preserveState: true,
      preserveScroll: true,
    });
  }, 300);
};
</script>
