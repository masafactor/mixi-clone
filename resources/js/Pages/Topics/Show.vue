<template>
  <div class="max-w-3xl mx-auto py-8 space-y-6">
    <h1 class="text-2xl font-bold">{{ topic.title }}</h1>
    <p class="text-gray-600">{{ topic.content }}</p>

    <div>
      <h2 class="text-xl font-semibold mt-6 mb-2">コメント</h2>
      <div v-if="comments.length > 0">
        <div v-for="comment in comments" :key="comment.id" class="border-b py-2">
          <p class="font-semibold">{{ comment.user.name }}</p>
          <p>{{ comment.content }}</p>
        </div>
      </div>
      <div v-else class="text-gray-500">コメントはまだありません。</div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  topic: Object,
  comments: Array
})
</script>
