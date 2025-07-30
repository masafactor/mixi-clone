<template>
  <form @submit.prevent="submit">

    <div>
        <div v-if="flash.success" class="bg-green-100 text-green-800 p-2 rounded mb-4">
        {{ flash.success }}
        </div>
        <!-- その他のページ内容 -->
    </div>

    <div class="mb-4">
      <input v-model="form.title" type="text" placeholder="タイトル" class="border p-2 w-full" />
      <div v-if="form.errors.title" class="text-red-600 text-sm mt-1">{{ form.errors.title }}</div>
    </div>

    <div class="mb-4">
      <textarea v-model="form.body" placeholder="本文" class="border p-2 w-full h-40" />
      <div v-if="form.errors.body" class="text-red-600 text-sm mt-1">{{ form.errors.body }}</div>
    </div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2">投稿する</button>
  </form>
</template>

<script setup>
import { useForm,usePage } from '@inertiajs/vue3'

const form = useForm({
  title: '',
  body: ''
})

const submit = () => {
  form.post(route('diary.store'))
}


const { props } = usePage()
const flash = props.flash
</script>
