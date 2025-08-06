<template>
  <div class="max-w-sm mx-auto mt-32">
    <h2 class="text-xl font-bold mb-4">Login</h2>
    <form @submit.prevent="login">
      <input v-model="email" type="email" placeholder="Email" class="input input-bordered w-full mb-2" />
      <input v-model="password" type="password" placeholder="Password" class="input input-bordered w-full mb-2" />
      <button type="submit" class="btn btn-primary w-full" :disabled="loading">
        {{ loading ? 'Loading...' : 'Login' }}
      </button>
      <div v-if="error" class="text-red-500 mt-2">{{ error }}</div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)
const router = useRouter()

const login = async () => {
  loading.value = true
  error.value = ''
  try {
    const res = await axios.post('/api/login', {
      email: email.value,
      password: password.value,
    })
    localStorage.setItem('token', res.data.token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${res.data.token}`
    router.push('/')
  } catch (e) {
    error.value = e.response?.data?.message || 'Login gagal'
  }
  loading.value = false
}
</script>