
<template #header>

  <div class="flex max-w-5xl mx-auto py-8 gap-6">
    <!-- メインコンテンツ -->


         <!-- 友達申請一覧 -->
      <div v-if="friendRequests.length > 0" class="bg-white shadow rounded p-4 mb-6">
        <h2 class="font-bold mb-4">友達申請</h2>
        <div v-for="request in friendRequests" :key="request.id" class="mb-3 flex justify-between">
          <span>{{ request.sender.name }} さんから申請</span>
          <div>
            <button class="bg-green-500 text-white px-2 py-1 rounded mr-2"
                    @click="acceptRequest(request.id)">承認</button>
            <button class="bg-red-500 text-white px-2 py-1 rounded"
                    @click="rejectRequest(request.id)">拒否</button>
          </div>
        </div>
      </div>
      
    <div class="flex-1">
    <h2 class="font-bold text-lg mb-4">タイムライン</h2>
    <div v-for="item in timeline" :key="item.id" class="mb-4">
      <div v-if="item.type === 'diary'">
        📝 {{ item.user.name }} さんの日記更新:
        <a :href="item.link" class="text-blue-500 hover:underline">
            {{ item.title }}
        </a>
      </div>
      <div v-else-if="item.type === 'topic'">
        💬 {{ item.community.name }} の新着トピック: 
        <a :href="item.link" class="text-blue-500 hover:underline">
        {{ item.title }}
        </a>
      </div>
    </div>
  </div>
    <!-- サイドバー -->
    <aside class="w-80">

      <!-- 自分のページへのリンク -->
      <div class="bg-white shadow rounded p-4 mb-6">
      <Link :href="route('users.show', userName)" class="text-blue-500 underline ml-4">
      {{ userName }}のページ
      </Link>
      <br>
          <!-- プロフィール編集ページへのリンク -->
      <Link href="/profile/edit" class="text-blue-500 underline">
        プロフィールを編集
      </Link>
      </div>

      <div class="bg-white shadow rounded p-4 mb-6">
        <h2 class="font-bold mb-2 text-lg"> {{ userName }}の日記</h2>
        <div v-if="latestDiary">
          <p class="font-semibold">{{ latestDiary.title }}</p>
          <Link :href="route('diary.index')" class="text-blue-500 underline">
            もっと見る
          </Link>
        </div>
        <div v-else>
          <p class="text-gray-500 mb-2">まだ日記がありません</p>
          <Link :href="route('diary.create')" class="text-blue-500 underline">
            日記を書く
          </Link>
        </div>
      </div>

<div class="bg-white shadow rounded p-4">
  <h2 class="font-bold mb-2 text-lg">参加コミュニティ</h2>

  <ul v-if="communities && communities.length">
    <li v-for="c in communities" :key="c.id" class="border-b py-2">
      <a :href="route('communities.show', c.id)" class="text-blue-600 hover:underline">
        {{ c.name }}
      </a>
    </li>
  </ul>

  <p v-else class="text-gray-500">まだコミュニティがありません</p>
</div>
    </aside>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'


defineProps({
  latestDiary: Object,
  userName: String,
  friendRequests: Array,
  communities: { type: Array, default: () => [] },
   timeline: { type: Array, default: () => [] }
})



function acceptRequest(id) {
  router.patch(`/friend-request/${id}`)
}

function rejectRequest(id) {
  router.delete(`/friend-request/${id}`)
}

</script>
