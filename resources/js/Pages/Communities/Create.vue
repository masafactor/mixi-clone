<template>
  <div class="max-w-lg mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">コミュニティ作成</h1>
    <form @submit.prevent="submit">
      <!-- コミュニティ名 -->
      <div class="mb-4">
        <label class="block font-bold">コミュニティ名</label>
        <input v-model="form.name" class="w-full border rounded px-3 py-2" type="text" required />
      </div>

      <!-- 説明 -->
      <div class="mb-4">
        <label class="block font-bold">説明</label>
        <textarea v-model="form.description" class="w-full border rounded px-3 py-2"></textarea>
      </div>

      <!-- 公開範囲 -->
      <div class="mb-4">
        <label class="block font-bold">公開範囲</label>
        <select v-model="form.visibility" class="w-full border rounded px-3 py-2">
          <option value="public">公開</option>
          <option value="approval">承認制</option>
          <option value="private">非公開</option>
        </select>
      </div>

      <!-- カテゴリ選択 -->
      <div class="mb-4">
        <label class="block font-bold">カテゴリ</label>
        <select v-model="form.category_id" class="w-full border rounded px-3 py-2">
          <option value="">選択してください</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">
            {{ cat.name }}
          </option>
        </select>
      </div>

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        作成
      </button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
  categories: Array
})

const form = useForm({
  name: '',
  description: '',
  visibility: 'approval',
  category_id: ''
})

function submit() {
  form.post(route('communities.store'))
}
</script>
