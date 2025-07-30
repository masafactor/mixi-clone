<template>
  <div class="max-w-2xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">プロフィール編集</h1>

  <div v-if="page.props.flash.success" class="mb-4 p-3 bg-green-100 text-green-800 rounded">
    {{ page.props.flash.success }}
  </div>

    <form @submit.prevent="updateProfile" enctype="multipart/form-data">
      <!-- ニックネーム -->
      <div class="mb-4">
        <label class="block font-bold">ニックネーム</label>
        <input v-model="form.nickname" class="w-full border rounded px-3 py-2" type="text" />
        <VisibilitySelect v-model="form.nickname_visibility" />
      </div>

      <!-- 自己紹介 -->
      <div class="mb-4">
        <label class="block font-bold">自己紹介</label>
        <textarea v-model="form.bio" class="w-full border rounded px-3 py-2" />
        <VisibilitySelect v-model="form.bio_visibility" />
      </div>

      <!-- 性別 -->
      <div class="mb-4">
        <label class="block font-bold">性別</label>
        <select v-model="form.gender" class="w-full border rounded px-3 py-2">
          <option value="undisclosed">未設定</option>
          <option value="male">男性</option>
          <option value="female">女性</option>
          <option value="other">その他</option>
        </select>
        <VisibilitySelect v-model="form.gender_visibility" />
      </div>

      <!-- 誕生日 -->
      <div class="mb-4">
        <label class="block font-bold">誕生日</label>
        <input v-model="form.birthdate" class="w-full border rounded px-3 py-2" type="date" />
        <VisibilitySelect v-model="form.birthdate_visibility" />
      </div>

      <!-- 都道府県 -->
      <div class="mb-4">
        <label class="block font-bold">都道府県</label>
        <select v-model="form.prefecture_id" class="w-full border rounded px-3 py-2">
          <option value="">選択してください</option>
          <option v-for="pref in props.prefectures" :key="pref.id" :value="pref.id">
            {{ pref.name }}
          </option>
        </select>
        <VisibilitySelect v-model="form.location_visibility" />
      </div>



      <!-- 更新ボタン -->
      <button class="bg-blue-500 text-white px-4 py-2 rounded">更新</button>
    </form>

     <!-- 画像アップロードページへの遷移ボタン -->
<Link href="/profile/avatar" class="text-blue-600 underline">
  アバター画像を変更する
</Link>

  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'


import VisibilitySelect from '@/Components/VisibilitySelect.vue'

const page = usePage() 
const avatarUrl = ref(null)

const props = defineProps({ profile: Object, prefectures: Array})
console.log(props.prefectures)
const form = reactive({
  nickname: props.profile.nickname ?? '',
  nickname_visibility: props.profile.nickname_visibility ?? 'friends',
  bio: props.profile.bio ?? '',
  bio_visibility: props.profile.bio_visibility ?? 'friends',
  gender: props.profile.gender ?? 'undisclosed',
  gender_visibility: props.profile.gender_visibility ?? 'private',
  birthdate: props.profile.birthdate ?? '',
  birthdate_visibility: props.profile.birthdate_visibility ?? 'private',
  prefecture_id: props.profile.prefecture_id ?? '',
  location_visibility: props.profile.location_visibility ?? 'friends',
})



function updateProfile() {
  router.put('/profile', form)
}
</script>
