<template>
  <div class="max-w-3xl mx-auto py-8">
    <h1 class="text-xl font-bold mb-4">最近の足あと</h1>

    <ul>
      <li v-for="fp in footprints.data" :key="fp.id"
          class="flex justify-between items-center py-2 border-b last:border-0">
        <div class="flex items-center gap-2">
          <Link :href="fp.viewer.link">
            <img :src="fp.viewer.icon" class="h-8 w-8 rounded-full object-cover border" alt="" />
          </Link>
          <Link :href="fp.viewer.link" class="text-blue-600 hover:underline">
            {{ fp.viewer.name }}
          </Link>
        </div>
        <span class="text-xs text-gray-500">{{ new Date(fp.updated_at).toLocaleString() }}</span>
      </li>
    </ul>

    <!-- ページネーションはお好みで -->
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ footprints: Object })
</script>
