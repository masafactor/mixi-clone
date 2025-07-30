<!-- resources/js/Components/VisibilitySelect.vue -->
<template>
  <select
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    class="w-full border rounded px-3 py-2 mt-2 text-sm text-gray-700"
  >
    <option value="public">全体公開</option>
    <option value="friends">友達のみ</option>
    <option value="private">非公開</option>
  </select>
</template>

<script setup>
defineProps(['modelValue'])
defineEmits(['update:modelValue'])
</script>
