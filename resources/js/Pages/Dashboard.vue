<template>
  <AuthenticatedLayout>
    <template #header>Dashboard</template>

    <div class="space-y-6">
      <!-- Stats cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border p-6">
          <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm text-gray-500">Total Licencias</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.total_licencias }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
          <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm text-gray-500">Total Secciones</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.total_secciones }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
          <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm text-gray-500">Sistemas</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.secciones_por_sistema.length }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Secciones por sistema -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Secciones por Sistema</h3>
          <div class="space-y-3">
            <div
              v-for="sistema in stats.secciones_por_sistema"
              :key="sistema.sistema_id"
              class="flex items-center justify-between"
            >
              <span class="text-gray-600">{{ sistema.nombre }}</span>
              <div class="flex items-center gap-3">
                <div class="w-32 h-2 bg-gray-100 rounded-full overflow-hidden">
                  <div
                    class="h-full rounded-full"
                    :class="getBarColor(sistema.sistema_id)"
                    :style="{ width: `${(sistema.total / maxSecciones) * 100}%` }"
                  />
                </div>
                <span class="text-sm font-medium text-gray-900 w-8 text-right">{{ sistema.total }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Licencias recientes -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Licencias Recientes</h3>
          <div class="space-y-3">
            <Link
              v-for="licencia in stats.licencias_recientes"
              :key="licencia.id"
              :href="route('licencias.show', licencia.id)"
              class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors"
            >
              <div>
                <p class="font-medium text-gray-900">{{ licencia.nombre }}</p>
                <p class="text-sm text-gray-500">ID: {{ licencia.id }}</p>
              </div>
              <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                {{ licencia.pais || 'N/A' }}
              </span>
            </Link>
          </div>

          <Link
            :href="route('licencias.index')"
            class="block text-center text-sm text-blue-600 hover:text-blue-700 mt-4 pt-4 border-t"
          >
            Ver todas las licencias
          </Link>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  stats: Object,
});

const maxSecciones = computed(() => {
  return Math.max(...props.stats.secciones_por_sistema.map(s => s.total));
});

const getBarColor = (id) => {
  const colors = {
    1: 'bg-blue-500',
    2: 'bg-green-500',
    3: 'bg-purple-500',
    4: 'bg-orange-500',
    5: 'bg-red-500',
    6: 'bg-gray-500',
    7: 'bg-teal-500',
  };
  return colors[id] || 'bg-gray-500';
};
</script>
