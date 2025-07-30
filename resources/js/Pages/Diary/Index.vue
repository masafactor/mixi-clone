<template>
  <div class="max-w-2xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">日記一覧</h1>

    <div v-if="$page.props.flash.success" class="bg-green-100 text-green-800 p-2 rounded mb-4">
      {{ $page.props.flash.success }}
    </div>

    <div v-if="diaries.length === 0" class="text-gray-500">投稿がありません。</div>

    <div v-for="diary in diaries" :key="diary.id" class="border p-4 mb-4 rounded shadow">
      <h2 class="text-xl font-semibold">{{ diary.title }}</h2>
      <p class="text-gray-600 text-sm mb-2">{{ formatDate(diary.created_at) }}</p>
      <p class="mb-2">{{ diary.body }}</p>
      <div class="text-right">
        <Link :href="route('diary.edit', diary.id)" class="text-blue-600 underline mr-2">編集</Link>
        <button @click="deleteDiary(diary.id)" class="text-red-600">削除</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  diaries: Array,
})

const page = usePage()

function formatDate(date) {
  return new Date(date).toLocaleDateString()
}

function deleteDiary(id) {
  if (confirm('本当に削除しますか？')) {
    router.delete(route('diary.destroy', id))
  }
}
</script>
