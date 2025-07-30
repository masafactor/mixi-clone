<template>
  <div class="max-w-md mx-auto py-12">
    <h1 class="text-2xl font-bold mb-6">ユーザー名を設定してください</h1>

    <form @submit.prevent="submit">
      <div class="mb-4">
        <label for="username" class="block mb-1">ユーザー名</label>
        <input
            v-model="form.username"
            :placeholder="`例: ${fakeSuggestion}`"
            :class="{ 'border-red-500': form.errors.username }"
          />
          <div v-if="form.username === currentNickname" class="text-red-500">
            GitHubのユーザー名とは違う名前を入力してください。
          </div>
          <div v-if="form.errors.username" class="text-red-500">
            {{ form.errors.username }}
          </div>
          <div v-if="suggestion">おすすめ: {{ suggestion }}</div>

      </div>


      <button
        type="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        登録
      </button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import axios from 'axios'

const suggestion = ref('')

const props = defineProps({
  currentNickname: String
})

async function fetchSuggestion() {
  if (!form.username) return
  try {
    const res = await axios.get(`/username-suggestion/${form.username}`)
    suggestion.value = res.data.suggestion
  } catch (e) {
    suggestion.value = ''
  }
}

const form = useForm({
  username: '',
})

function submit() {
  form.post(route('username.store'), {
    onError: () => {
      // バリデーションエラーが出たら候補取得
      fetchSuggestion()
    }
  })
}
</script>
