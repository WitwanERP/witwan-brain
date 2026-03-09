<template>
  <div class="flex gap-2 flex-wrap">
    <button
      @click="$emit('change', null)"
      class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
      :class="!modelValue ? 'bg-slate-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
    >
      Todos
    </button>

    <button
      v-for="(nombre, id) in sistemas"
      :key="id"
      @click="$emit('change', Number(id))"
      class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
      :class="modelValue === Number(id)
        ? getSistemaActiveClass(Number(id))
        : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
    >
      {{ nombre }}
    </button>
  </div>
</template>

<script setup>
defineProps({
  sistemas: Object,
  modelValue: [Number, null],
});

defineEmits(['change']);

const getSistemaActiveClass = (id) => {
  const classes = {
    1: 'bg-blue-600 text-white',
    2: 'bg-green-600 text-white',
    3: 'bg-purple-600 text-white',
    4: 'bg-orange-600 text-white',
    5: 'bg-red-600 text-white',
    6: 'bg-gray-700 text-white',
    7: 'bg-teal-600 text-white',
  };
  return classes[id] || 'bg-gray-700 text-white';
};
</script>
