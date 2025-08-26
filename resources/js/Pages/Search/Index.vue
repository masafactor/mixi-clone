<template>
  <div class="max-w-4xl mx-auto py-8 space-y-6">
    <h1 class="text-2xl font-bold">「{{ q }}」の検索結果</h1>

    <!-- 日記 -->
    <div v-if="diaries.length" class="bg-white shadow rounded p-4 mb-6">
    <h2 class="font-bold mb-2 text-lg">日記</h2>
    <ul>
        <li v-for="d in diaries" :key="d.id" class="border-b py-2">
        <Link :href="route('diary.show', d.id)" class="text-blue-600 hover:underline">
            {{ d.title }}
        </Link>
        <span class="text-gray-500 text-sm ml-2">（{{ d.user.username }} さん）</span>
        </li>
    </ul>
    </div>


<div v-if="communities.length" class="bg-white shadow rounded p-4 mb-6">
  <h2 class="font-bold mb-2 text-lg">コミュニティ</h2>
  <ul>
    <li v-for="c in communities" :key="c.id" class="border-b py-2">
      <Link :href="route('communities.show', c.id)" class="text-blue-600 hover:underline">
        {{ c.name }}
      </Link>
    </li>
  </ul>
</div>

<div v-if="users.length" class="bg-white shadow rounded p-4 mb-6">
  <h2 class="font-bold mb-2 text-lg">ユーザー</h2>
  <ul>
    <li v-for="u in users" :key="u.id" class="flex items-center gap-2 border-b py-2">
      <img v-if="u.profile_photo_url" :src="u.profile_photo_url"
           class="h-6 w-6 rounded-full object-cover border" />
      <Link :href="route('users.show', u.username)" class="text-blue-600 hover:underline">
        {{ u.username }}
      </Link>
    </li>
  </ul>
</div>

  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  q: { type: String, default: '' },
  diaries: { type: Array, default: () => [] },
  communities: { type: Array, default: () => [] },
  users: { type: Array, default: () => [] },
})
</script>
