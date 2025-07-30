<template>
  <div class="flex max-w-5xl mx-auto py-8 gap-6">
    <!-- メインコンテンツ -->
    <!-- 自分のページへのリンク -->
        <Link :href="route('users.show', userName)" class="text-blue-500 underline ml-4">
          自分のページ
        </Link>



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
      <h1 class="text-2xl font-bold mb-6">ホーム</h1>
      <!-- ここに友達の日記フィード(今後追加) -->
      <div class="bg-white shadow rounded p-4 text-gray-700">
        <p>ここにタイムラインが表示されます。</p>
      </div>
    </div>

    <!-- サイドバー -->
    <aside class="w-80">
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
        <p class="text-gray-500">まだコミュニティがありません</p>
      </div>
      <!-- プロフィール編集ページへのリンク -->
        <Link href="/profile/edit" class="text-blue-500 underline">
        プロフィールを編集
    </Link>
    </aside>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'


defineProps({
  latestDiary: Object,
  userName: String,
  friendRequests: Array
})



function acceptRequest(id) {
  router.patch(`/friend-request/${id}`)
}

function rejectRequest(id) {
  router.delete(`/friend-request/${id}`)
}

</script>
