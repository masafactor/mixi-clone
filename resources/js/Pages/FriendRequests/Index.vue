<template>
  <div>
    <h1>フレンド申請一覧</h1>
    <ul>
      <li v-for="request in requests" :key="request.id">
        {{ request.sender.name }} からの申請
        <!-- 承認・拒否ボタンもここに配置可 -->
      </li>
    </ul>
  </div>
</template>

<script setup>
defineProps({
  requests: Array
});
</script>
