<template>
  <div class="max-w-xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">日記を編集</h1>

    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block mb-1">タイトル</label>
        <input v-model="form.title" type="text" class="w-full border p-2 rounded" />
        <div v-if="form.errors.title" class="text-red-500">{{ form.errors.title }}</div>
      </div>

      <div class="mb-4">
        <label class="block mb-1">本文</label>
        <textarea v-model="form.body" class="w-full border p-2 rounded" rows="5" />
        <div v-if="form.errors.body" class="text-red-500">{{ form.errors.body }}</div>
      </div>

      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">更新</button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  diary: Object,
})

const form = useForm({
  title: props.diary.title,
  body: props.diary.body,
})

function submit() {
  form.put(route('diary.update', props.diary.id))
}
</script>
