<template>
  <div class="max-w-3xl mx-auto py-8 space-y-6">
    <!-- トピック詳細 -->
    <div class="bg-white shadow rounded p-6">
      <h1 class="text-2xl font-bold mb-2">{{ topic.title }}</h1>
      <p class="text-gray-500 text-sm mb-4">
        作成者: {{ topic.user?.name }} / {{ topic.created_at }}
      </p>
      <p class="text-gray-700 whitespace-pre-line">{{ topic.body }}</p>
    </div>

    <!-- コメント一覧 -->
    <div class="bg-white shadow rounded p-6">
      <h2 class="text-xl font-bold mb-4">コメント一覧</h2>

      <div v-if="comments && comments.length">
        <div v-for="comment in comments" :key="comment.id" class="border-b py-3">
          <div class="flex items-start justify-between">
            <div class="pr-3">
              <p class="font-semibold">{{ comment.user.name }}</p>

              <!-- 編集モード -->
              <div v-if="editingId === comment.id">
                <textarea v-model="editForm.body" class="w-full border rounded px-3 py-2 mt-1"></textarea>
                <div class="mt-2 space-x-2">
                  <button @click="saveEdit(comment)" class="bg-blue-600 text-white px-3 py-1 rounded">保存</button>
                  <button @click="cancelEdit" class="bg-gray-300 px-3 py-1 rounded">キャンセル</button>
                </div>
              </div>
              <div v-else>
                <p class="text-gray-700 whitespace-pre-line">{{ comment.body }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ comment.created_at }}</p>
              </div>
            </div>

            <!-- 自分 or 管理者のみ操作 -->
            <div v-if="canManage(comment)" class="shrink-0 space-x-2">
              <button v-if="editingId !== comment.id" @click="startEdit(comment)" class="text-blue-600 hover:underline">編集</button>
              <button @click="destroyComment(comment)" class="text-red-600 hover:underline">削除</button>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="text-gray-500">まだコメントはありません。</div>
    </div>

    <!-- コメント投稿 -->
    <div class="bg-white shadow rounded p-6">
      <h2 class="text-xl font-bold mb-4">コメントを投稿する</h2>
      <form @submit.prevent="submitComment">
        <textarea v-model="createForm.body" class="w-full border rounded px-3 py-2 mb-4" required></textarea>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">投稿</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, router, usePage } from '@inertiajs/vue3'

const props = defineProps({
  community: Object,
  topic: Object,
  comments: { type: Array, default: () => [] },
  isAdmin: { type: Boolean, default: false },
})

/* Inertia の $page 代わり */
const page = usePage()
const me = page.props.auth?.user

/* 投稿（DBカラムは body） */
const createForm = useForm({ body: '' })
function submitComment() {
  createForm.post(
    route('comments.store', { community: props.community.id, topic: props.topic.id }),
    { preserveScroll: true, onSuccess: () => createForm.reset('body') }
  )
}

/* 編集 */
const editingId = ref(null)
const editForm = useForm({ body: '' })

function startEdit(comment) {
  editingId.value = comment.id
  editForm.body = comment.body
}
function cancelEdit() {
  editingId.value = null
  editForm.reset('body')
}
function saveEdit(comment) {
  editForm.patch(
    route('comments.update', {
      community: props.community.id,
      topic: props.topic.id,
      comment: comment.id,
    }),
    { preserveScroll: true, onSuccess: () => cancelEdit() }
  )
}

/* 削除 */
function destroyComment(comment) {
  if (!confirm('このコメントを削除しますか？')) return
  router.delete(
    route('comments.destroy', {
      community: props.community.id,
      topic: props.topic.id,
      comment: comment.id,
    }),
    { preserveScroll: true }
  )
}

/* 自分のコメント or 管理者 */
function canManage(comment) {
  return props.isAdmin || (me && me.id === comment.user_id)
}
</script>
