<template>
  <div>
    <h1 class="text-2xl font-bold">{{ community.name }}</h1>
    <p class="text-gray-500">カテゴリ: {{ community.category.name }}</p>


<div class="mt-4">
  <p class="font-bold">メンバー数: {{ props.membersCount }}</p>
  <ul>
    <li v-for="member in props.members" :key="member.id">
      {{ member.name }}
    </li>
  </ul>

  <div v-if="!props.isMember && !props.isPending">
    <form @submit.prevent="joinCommunity">
      <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">参加</button>
    </form>
  </div>
  <div v-else-if="props.isPending">
    <p class="text-gray-500">参加申請中...</p>
  </div>
  <div v-else>
    <form @submit.prevent="leaveCommunity">
      <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">退会</button>
    </form>
  </div>
</div>
<div v-if="props.isAdmin && props.pendingMembers && props.pendingMembers.length > 0" class="mt-8">
  <h3 class="font-bold mb-2">承認待ちメンバー</h3>
  <ul>
    <li v-for="member in props.pendingMembers" :key="member.id" class="flex justify-between">
      {{ member.name }}
      <div>
        <button @click="approveMember(member.id)" class="bg-blue-500 text-white px-2 py-1 rounded">承認</button>
        <button @click="rejectMember(member.id)" class="bg-red-500 text-white px-2 py-1 rounded">拒否</button>
      </div>
    </li>
  </ul>
</div>

    <!-- メンバー一覧 -->
    <div class="mt-6">
      <h2 class="text-xl font-bold">メンバー ({{ memberCount }}人)</h2>
      <div v-if="community.users.length">
        <ul class="list-disc pl-5">
          <li v-for="member in members" :key="member.id">
            {{ member.name }}
          </li>
        </ul>
      </div>
      <p v-else class="text-gray-500">まだメンバーはいません。</p>
    </div>

<!-- トピック一覧ページへのリンク -->
    <div class="bg-white shadow rounded p-6 text-center">
      <Link
        :href="route('topics.index', props.community.id)"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        トピック一覧へ
      </Link>
    </div>

  </div>
</template>

<script setup>

import { router,Link } from '@inertiajs/vue3'


const props = defineProps({
  community: Object,
  isMember: Boolean,
  isPending: Boolean,
  isAdmin: Boolean,
  memberCount: Number,
   members: Array,
   pendingMembers: {
    type: Array,
    default: () => []
  }
})


function joinCommunity() {
  router.post(route('communities.join', props.community.id))
}
function leaveCommunity() {
  router.delete(route('communities.leave', props.community.id))
}

// 承認
// 承認処理
function approveMember(userId) {
  router.patch(route('communities.approve', [props.community.id, userId]))
}

// 拒否処理
function rejectMember(userId) {
  router.delete(route('communities.reject', [props.community.id, userId]))
}

</script>
