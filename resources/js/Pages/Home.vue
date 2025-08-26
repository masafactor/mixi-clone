
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
    <!-- ユーザーアイコン -->


      <div v-if="item.type === 'diary'" class="flex items-center gap-2">
         <Link :href="route('users.show', item.user.name)">
            <img
              v-if="item.user?.icon"
              :src="cacheBust(item.user.icon)"
              class="h-8 w-8 rounded-full object-cover border"
              alt="user icon"
            />
          </Link>
        {{ item.user.name }} さんの日記更新:
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
      <div class="mb-4 flex gap-2">
        <input v-model="keyword" type="text" placeholder="日記・コミュ・ユーザーを検索"
              class="border rounded px-3 py-2 flex-1" @keyup.enter="search" />
        <button @click="search" class="bg-blue-600 text-white px-4 py-2 rounded">検索</button>
      </div>
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


<!-- 最近の足あと -->
<div class="bg-white shadow rounded p-4 mb-6">
  <div class="flex items-center justify-between mb-2">
    <h2 class="font-bold text-lg">最近の足あと</h2>
    <Link href="/footprints" class="text-blue-500 underline">もっと見る</Link>
  </div>

  <ul v-if="footprints?.length">
    <li v-for="fp in footprints" :key="fp.id"
        class="flex justify-between items-center py-2 border-b last:border-0">

      <div class="flex items-center gap-2">
        <Link :href="route('users.show', fp.viewer.username)">
          <img
            v-if="fp.viewer?.icon || fp.viewer?.profile_photo_url"
            :src="cacheBust(fp.viewer.icon || fp.viewer.profile_photo_url)"
            class="h-6 w-6 rounded-full object-cover border"
            alt="viewer icon"
          />
        </Link>
        <Link :href="route('users.show', fp.viewer.username)" class="text-blue-600 hover:underline">
          {{ fp.viewer.name }}
        </Link>
      </div>

      <span class="text-xs text-gray-500">{{ fmt(fp.updated_at) }}</span>
    </li>
  </ul>

  <p v-else class="text-gray-500">まだ足あとがありません</p>
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
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'

const keyword = ref('')

defineOptions({ layout: AppLayout })

defineProps({
  latestDiary: Object,
  userName: String,
  friendRequests: Array,
  communities: { type: Array, default: () => [] },
  timeline: { type: Array, default: () => [] },
  footprints: { type: Array, default: () => [] },
})

// 簡単フォーマッタ（任意で dayjs 等に置換）
function fmt(ts) {
  return new Date(ts).toLocaleString()
}

function acceptRequest(id) {
  router.patch(`/friend-request/${id}`)
}

function rejectRequest(id) {
  router.delete(`/friend-request/${id}`)
}

// 1回の描画で固定したいなら mount 時のトークンでもOK
const bustToken = Date.now()
function cacheBust(url) {
  if (!url) return ''
  return `${url}${url.includes('?') ? '&' : '?'}t=${bustToken}`
}

function search() {
  if (!keyword.value.trim()) return
  router.get(route('search'), { q: keyword.value.trim() })
}

</script>
