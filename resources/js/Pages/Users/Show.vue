<template>
  <div class="max-w-5xl mx-auto py-8 space-y-6">
    <!-- ユーザー情報 -->
    <div class="bg-white shadow rounded p-6">
      <h1 class="text-2xl font-bold mb-2">{{ profileUser.username ?? profileUser.name }}</h1>
      <p class="text-gray-600">
        {{ profileUser.profile?.bio ?? '自己紹介はまだありません。' }}
      </p>

      <div class="mb-6">
        <!-- 都道府県 -->
        <p v-if="profileUser.profile?.prefecture_id" class="text-gray-500 mt-2">
          都道府県: {{ prefectureName(profileUser.profile.prefecture_id) }}
        </p>
        <p v-else class="text-gray-400 mt-2">
          都道府県: 非公開
        </p>

        <!-- 性別 -->
        <p v-if="profileUser.profile?.gender" class="text-gray-500 mt-1">
          性別: {{ genderLabel[profileUser.profile.gender] ?? '未設定' }}
        </p>
        <p v-else class="text-gray-400 mt-1">
          性別: 非公開
        </p>

        <!-- 誕生日 -->
        <p v-if="profileUser.profile?.birthdate" class="text-gray-500 mt-1">
          誕生日: {{ profileUser.profile.birthdate }}
        </p>
        <p v-else class="text-gray-400 mt-1">
          誕生日: 非公開
        </p>
      </div>

      <!-- メッセージ表示 -->
      <div v-if="$page.props.flash.success" class="bg-green-100 text-green-800 px-4 py-2 rounded">
        {{ $page.props.flash.success }}
      </div>

      <!-- 友達申請ボタン -->
      <div v-if="!isOwner" class="mt-4">
        <template v-if="friendStatus === 'friend'">
          <span class="text-green-600 font-bold">フレンド</span>
          <button
            @click="unfriend(profileUser.id)"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
          >
            フレンド解除
          </button>
        </template>
        <template v-else-if="friendStatus === 'pending'">
          <span class="text-yellow-600 font-bold">申請中</span>
        </template>
        <template v-else>
          <button
            @click="sendRequest(profileUser.id)"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          >
            友達申請する
          </button>
        </template>
      </div>
    </div>

    <!-- 日記一覧 -->
    <div class="bg-white shadow rounded p-6">
      <h2 class="text-xl font-bold mb-4">日記一覧</h2>
      <div v-if="diaries.length > 0">
        <ul>
          <li v-for="diary in diaries" :key="diary.id" class="mb-2">
            <Link :href="route('diary.show', diary.id)" class="text-blue-500 underline">
              {{ diary.title }}
            </Link>
            <span class="text-sm text-gray-500 ml-2">{{ diary.created_at }}</span>
          </li>
        </ul>
      </div>
      <div v-else>
        <p class="text-gray-500">日記はありません。</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  profileUser: Object,
  diaries: Array,
  isOwner: Boolean,
  friendStatus: String,
  prefectures: Array,
})

const genderLabel = {
  male: '男性',
  female: '女性',
  other: 'その他',
  undisclosed: '未設定'
}

// ID→名前変換
function prefectureName(id) {
  const pref = props.prefectures.find(p => p.id === id)
  return pref ? pref.name : '不明'
}

function sendRequest(receiverId) {
  router.post(route('friend.request'), { receiver_id: receiverId })
}

function unfriend(friendId) {
  router.delete(`/friends/${friendId}`)
}
</script>
