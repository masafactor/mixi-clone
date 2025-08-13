<template>
  <div class="max-w-3xl mx-auto py-8 space-y-6">
    <h1 class="text-2xl font-bold">トピック一覧</h1>

    <div v-if="topics.length > 0">
      <div v-for="topic in topics" :key="topic.id" class="border-b py-2">
        <Link :href="route('topics.show', { community: community.id, topic: topic.id })"
              class="text-blue-500 underline">
          {{ topic.title }}
        </Link>
        <p class="text-gray-500 text-sm">作成者: {{ topic.user.name }}</p>
      </div>
    </div>
    <div v-else class="text-gray-500">トピックはまだありません。</div>

    <div class="bg-white shadow rounded p-6 text-center">
  <Link
    :href="route('topics.create', { community: community.id })"
    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
  >
    トピック作成へ
  </Link>
</div>

  </div>


</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  topics: {
    type: Array,
    default: () => []
  },
  community: {
    type: Object,
    required: true
  }
})

</script>
