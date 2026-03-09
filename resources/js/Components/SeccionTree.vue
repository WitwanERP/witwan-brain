<template>
  <div class="space-y-4">
    <!-- Grupo PERMISOS -->
    <div v-if="permisosTree.length">
      <div class="flex items-center gap-2 mb-2 px-3">
        <span class="text-xs font-semibold text-amber-600 uppercase tracking-wide">Permisos</span>
        <div class="flex-1 h-px bg-amber-200"></div>
      </div>
      <div class="space-y-1">
        <SeccionTreeItem
          v-for="node in permisosTree"
          :key="'p-' + node.id"
          :node="node"
          :depth="0"
          :selected-ids="selectedIds"
          :selectable="selectable"
          :editable="editable"
          @toggle="$emit('toggle', $event)"
        />
      </div>
    </div>

    <!-- Grupo SECCIONES -->
    <div v-if="seccionesTree.length">
      <div class="flex items-center gap-2 mb-2 px-3">
        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Secciones</span>
        <div class="flex-1 h-px bg-gray-200"></div>
      </div>
      <div class="space-y-1">
        <SeccionTreeItem
          v-for="node in seccionesTree"
          :key="'s-' + node.id"
          :node="node"
          :depth="0"
          :selected-ids="selectedIds"
          :selectable="selectable"
          :editable="editable"
          @toggle="$emit('toggle', $event)"
        />
      </div>
    </div>

    <div v-if="!tree.length" class="text-center py-8 text-gray-500">
      No hay secciones para mostrar.
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import SeccionTreeItem from './SeccionTreeItem.vue';

const props = defineProps({
  tree: {
    type: Array,
    required: true,
  },
  selectedIds: {
    type: Array,
    default: () => [],
  },
  selectable: {
    type: Boolean,
    default: false,
  },
  editable: {
    type: Boolean,
    default: false,
  },
});

defineEmits(['toggle']);

// Filtra recursivamente el árbol manteniendo solo nodos que son permisos o tienen hijos permisos
const filterPermisos = (nodes) => {
  return nodes
    .map(node => {
      const filteredChildren = node.children ? filterPermisos(node.children) : [];
      const hasPermisoChildren = filteredChildren.length > 0;
      const isPermiso = node.es_permiso;

      if (isPermiso || hasPermisoChildren) {
        return { ...node, children: filteredChildren };
      }
      return null;
    })
    .filter(Boolean);
};

// Filtra recursivamente el árbol manteniendo solo nodos que son secciones
const filterSecciones = (nodes) => {
  return nodes
    .map(node => {
      const filteredChildren = node.children ? filterSecciones(node.children) : [];
      const isSeccion = !node.es_permiso;

      if (isSeccion) {
        return { ...node, children: filteredChildren };
      }
      return null;
    })
    .filter(Boolean);
};

const permisosTree = computed(() => filterPermisos(props.tree));
const seccionesTree = computed(() => filterSecciones(props.tree));
</script>
