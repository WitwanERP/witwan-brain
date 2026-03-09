<template>
  <div>
    <div
      class="flex items-center gap-2 py-2 px-3 rounded-lg hover:bg-gray-50 transition-colors group"
      :style="{ paddingLeft: `${depth * 24 + 12}px` }"
    >
      <!-- Expand/Collapse -->
      <button
        v-if="node.children?.length"
        @click="expanded = !expanded"
        class="w-5 h-5 flex items-center justify-center text-gray-400 hover:text-gray-600"
      >
        <svg
          class="w-4 h-4 transition-transform"
          :class="{ 'rotate-90': expanded }"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
      <span v-else class="w-5" />

      <!-- Checkbox para selección -->
      <input
        v-if="selectable"
        type="checkbox"
        :checked="selectedIds.includes(node.id)"
        @change="$emit('toggle', node.id)"
        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
      />

      <!-- Nombre -->
      <span class="flex-1 text-sm" :class="node.es_permiso ? 'text-amber-600' : 'text-gray-900'">
        {{ node.nombre }}
      </span>

      <!-- Key badge -->
      <span v-if="node.key" class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-mono">
        {{ node.key }}
      </span>

      <!-- URI badge -->
      <span v-if="node.uri" class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded">
        {{ node.uri }}
      </span>

      <!-- Sistema badge -->
      <span
        class="text-xs px-2 py-0.5 rounded"
        :class="getSistemaBadgeClass(node.sistema_id)"
      >
        {{ getSistemaLabel(node.sistema_id) }}
      </span>

      <!-- Edit/Delete buttons -->
      <div v-if="editable" class="opacity-0 group-hover:opacity-100 flex gap-1">
        <Link
          :href="route('secciones.edit', node.id)"
          class="p-1 text-gray-400 hover:text-blue-600"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
        </Link>
        <button
          v-if="!node.children?.length"
          @click="confirmDelete(node)"
          class="p-1 text-gray-400 hover:text-red-600"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Children -->
    <div v-if="expanded && node.children?.length">
      <SeccionTreeItem
        v-for="child in node.children"
        :key="child.id"
        :node="child"
        :depth="depth + 1"
        :selected-ids="selectedIds"
        :selectable="selectable"
        :editable="editable"
        @toggle="$emit('toggle', $event)"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
  node: Object,
  depth: Number,
  selectedIds: Array,
  selectable: Boolean,
  editable: Boolean,
});

defineEmits(['toggle']);

const expanded = ref(props.depth < 2);

const getSistemaLabel = (id) => {
  const sistemas = {
    1: 'REC', 2: 'MAY', 3: 'MIN', 4: 'CON', 5: 'ADM', 6: 'CFG', 7: 'NAC'
  };
  return sistemas[id] || '?';
};

const getSistemaBadgeClass = (id) => {
  const classes = {
    1: 'bg-blue-100 text-blue-700',
    2: 'bg-green-100 text-green-700',
    3: 'bg-purple-100 text-purple-700',
    4: 'bg-orange-100 text-orange-700',
    5: 'bg-red-100 text-red-700',
    6: 'bg-gray-200 text-gray-700',
    7: 'bg-teal-100 text-teal-700',
  };
  return classes[id] || 'bg-gray-100 text-gray-700';
};

const confirmDelete = (node) => {
  if (confirm(`¿Eliminar la sección "${node.nombre}"?`)) {
    router.delete(route('secciones.destroy', node.id));
  }
};
</script>
