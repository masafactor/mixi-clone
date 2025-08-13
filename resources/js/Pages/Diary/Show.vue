<template>
  <div class="max-w-3xl mx-auto space-y-6 py-8">
    <div class="bg-white rounded shadow p-6">
      <h1 class="text-2xl font-bold mb-2">{{ diary.title }}</h1>
      <p class="text-sm text-gray-500 mb-4">
        作成者: {{ diary.user?.name ?? '不明' }} ／ {{ diary.created_at }}
      </p>
      <div class="prose whitespace-pre-line">{{ diary.body }}</div>
    </div>

    <div class="flex items-center gap-3">
      <a :href="route('diary.index')" class="text-blue-600 hover:underline">一覧に戻る</a>
      <!-- 自分の投稿なら編集ボタン（任意） -->
      <a
        v-if="me && me.id === diary.user?.id"
        :href="route('diary.edit', diary.id)"
        class="text-blue-600 hover:underline"
      >
        編集
      </a>
    </div>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import { onMounted } from 'vue'

const props = defineProps({
  diary: { type: Object, required: true , diaryId: Number },
})
onMounted(() => {
  router.post(route('footprints.store.diary', { diary: props.diaryId }), {}, {
    preserveScroll: true,
    preserveState: true,
    onError: () => {},
  })
})

const me = usePage().props.auth?.user
</script>
