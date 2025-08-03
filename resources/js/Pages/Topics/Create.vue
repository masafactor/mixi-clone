<template>
  <div class="max-w-lg mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">トピック作成 ({{ community.name }})</h1>
    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block font-bold">タイトル</label>
        <input v-model="form.title" class="w-full border rounded px-3 py-2" required />
      </div>
      <div class="mb-4">
        <label class="block font-bold">本文</label>
        <textarea v-model="form.body" class="w-full border rounded px-3 py-2" required></textarea>
      </div>
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">作成</button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({ community: Object })
const form = useForm({ title: '', body: '' })

function submit() {
  form.post(route('topics.store', props.community.id))
}
</script>
