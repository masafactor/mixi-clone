<template>
  <div class="max-w-md mx-auto py-10 space-y-6">
    <h1 class="text-2xl font-bold">アイコンの変更</h1>

    <div class="bg-white rounded shadow p-5 space-y-4">
      <div class="flex items-center gap-4">
        <img :src="photoUrl" class="h-16 w-16 rounded-full object-cover border" alt="icon" />
        <input type="file" accept="image/*" @change="onPick" />
 
        <p v-if="photoError" class="mt-2 bg-red-100 text-red-700 px-3 py-2 rounded">
          {{ photoError }}
        </p>
      </div>

      <div class="flex gap-3">
        <button type="button" class="bg-indigo-600 text-white px-4 py-2 rounded"
                :disabled="!file" @click="upload">
          アイコンを更新
        </button>
        <button type="button" class="px-4 py-2 rounded border text-red-600 border-red-300"
                @click="destroy">
          アイコンを削除
        </button>
        <Link href="/profile/edit" class="ml-auto text-blue-600 underline">プロフィール編集に戻る</Link>
      </div>

      <p v-if="$page.props.flash?.success"
         class="mt-2 bg-green-100 text-green-800 px-3 py-2 rounded">
        {{ $page.props.flash.success }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.user || page.props.auth.user

const file = ref(null)
const photoUrl = ref(user?.profile_photo_url || '')
const photoError = ref('')  

watch(() => page.props.auth?.user?.profile_photo_url, (v) => {
  if (!v) { photoUrl.value = ''; return }
  // キャッシュ回避
  photoUrl.value = `${v}${v.includes('?') ? '&' : '?'}t=${Date.now()}`
})

function onPick(e) {
  file.value = e.target.files?.[0] ?? null
}

function upload() {
  if (!file.value) return
  const u = page.props.auth.user
  const fd = new FormData()
  // Jetstream/Fortify 仕様：name/email 必須
  fd.append('name', u.name ?? '')
  fd.append('email', u.email ?? '')
  fd.append('photo', file.value)
  fd.append('_method', 'PUT')

  router.post('/user/profile-information', fd, {
    forceFormData: true,
    preserveScroll: true,
    errorBag: 'updateProfileInformation',              // ★これが重要
    onError: (errors) => { photoError.value = errors.photo || '' }, // ←表示用に保存
    onSuccess: () => {
      photoError.value = ''
      file.value = null
      // auth だけ再取得して即反映
      router.reload({ only: ['auth'] })
    },
  })
}

function destroy() {
  router.delete('/user/profile-photo', {
    preserveScroll: true,
    onSuccess: () => router.reload({ only: ['auth'] }),
  })
}
</script>
