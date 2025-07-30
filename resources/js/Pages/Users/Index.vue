<template>
  <div>
    <h2>ユーザー一覧</h2>
    <div v-for="user in users" :key="user.id" class="mb-4 p-2 border">
      <p>{{ user.name }}</p>

      <template v-if="user.friend_status === 'none'">
        <button @click="sendRequest(user.id)">フレンド申請</button>
      </template>
      <template v-else-if="user.friend_status === 'pending'">
        <span>申請中</span>
      </template>
      <template v-else-if="user.friend_status === 'friend'">
        <span>フレンド</span>
      </template>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { defineProps } from 'vue'
import route from 'ziggy-js'

const props = defineProps({
  users: Array,
})

function sendRequest(id) {
  router.post('/friend-requests', { receiver_id: id })
}
</script>
