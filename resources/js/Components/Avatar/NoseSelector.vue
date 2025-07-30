<template>
  <div class="mb-4">
    <label class="block mb-1 font-bold">鼻のタイプ</label>
    <select v-model="selected" class="border rounded px-2 py-1">
      <option value="none">なし</option>
      <option value="dot">点鼻</option>
      <option value="line">ライン鼻</option>
      <option value="triangle">三角鼻</option>
    </select>
  </div>
</template>

<script setup>
import { ref, watch, defineEmits } from 'vue'

const emits = defineEmits(['update'])
const selected = ref('dot')

watch(selected, (val) => {
  emits('update', val)
})
</script>
