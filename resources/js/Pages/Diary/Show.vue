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
      <!-- 自分の投稿なら編集ボタン -->
      <a
        v-if="me && me.id === diary.user?.id"
        :href="route('diary.edit', diary.id)"
        class="text-blue-600 hover:underline"
      >編集</a>
    </div>
  </div>
</template>

<script setup>
import { usePage , router} from '@inertiajs/vue3'
import { onMounted } from 'vue'

const props = defineProps({
  diary: { type: Object, required: true , diaryId: Number },
})


onMounted(() => {
  // 自分の投稿は送らない & 一度だけ送る
  if (me?.id && props.diary?.user?.id && me.id === props.diary.user.id) return

  router.post(
    route('footprints.diary', { diary: props.diary.id }),
    {},
    { preserveScroll: true, preserveState: true }
  )
})


const me = usePage().props.auth?.user
</script>
