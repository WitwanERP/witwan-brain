<template>
  <div class="space-y-1">
    <SeccionTreeItem
      v-for="node in tree"
      :key="node.id"
      :node="node"
      :depth="0"
      :selected-ids="selectedIds"
      :selectable="selectable"
      :editable="editable"
      @toggle="$emit('toggle', $event)"
    />
    <div v-if="!tree.length" class="text-center py-8 text-gray-500">
      No hay secciones para mostrar.
    </div>
  </div>
</template>

<script setup>
import SeccionTreeItem from './SeccionTreeItem.vue';

defineProps({
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
</script>
