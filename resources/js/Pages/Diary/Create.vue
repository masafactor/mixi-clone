<template>
<div class="max-w-2xl mx-auto py-8">
  <form @submit.prevent="submit">

    <div>
        <div v-if="flash.success" class="bg-green-100 text-green-800 p-2 rounded mb-4">
        {{ flash.success }}
        </div>
        <!-- その他のページ内容 -->
    </div>
    <h1 class="text-2xl font-bold mb-4">ブログの新規作成</h1>
    <div class="mb-4">
      <input v-model="form.title" type="text" placeholder="タイトル" class="border p-2 w-full" />
      <div v-if="form.errors.title" class="text-red-600 text-sm mt-1">{{ form.errors.title }}</div>
    </div>

    <div class="mb-4">
      <textarea v-model="form.body" placeholder="本文" class="border p-2 w-full h-40" />
      <div v-if="form.errors.body" class="text-red-600 text-sm mt-1">{{ form.errors.body }}</div>
    </div>

   <!-- 公開範囲 -->
    <div>
      <label class="block text-sm mb-1">公開範囲</label>
      <select v-model="form.visibility" class="border p-2 w-full">
        <option value="public">全体公開</option>
        <option value="friends">友達まで</option>
        <option value="private">非公開</option>
      </select>
      <div v-if="form.errors.visibility" class="text-red-600 text-sm mt-1">{{ form.errors.visibility }}</div>
    </div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2">投稿する</button>
  </form>
  </div>
</template>

<script setup>
import { useForm,usePage } from '@inertiajs/vue3'

const form = useForm({
  title: '',
  body: '',
  visibility: 'public',
})

const submit = () => {
  form.post(route('diary.store'))
}


const { props } = usePage()
const flash = props.flash
</script>
