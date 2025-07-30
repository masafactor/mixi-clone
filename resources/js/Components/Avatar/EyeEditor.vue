<template>
  <div class="mb-4">
    <label class="block mb-1 font-bold">目のタイプ</label>
    <select v-model="eyeType" class="border rounded px-2 py-1">
      <option value="normal">ノーマル</option>
      <option value="tare">たれ目</option>
      <option value="tsuri">つり目</option>
      <option value="jito">ジト目</option>
    </select>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const emit = defineEmits(['change'])

const eyeType = ref('normal')

watch(eyeType, () => {
  emit('change', eyeType.value)
})
</script>
