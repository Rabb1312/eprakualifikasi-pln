<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-2">Welcome to E-Prakualifikasi PLN</h1>
    <p class="text-gray-600">Ini halaman utama.</p>
    <button class="btn btn-error mt-4" @click="logout">Logout</button>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter();

const logout = async () => {
  try {
    await axios.post('/api/logout', {}, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      }
    });
  } catch (e) {
    // Silent error
  }
  localStorage.removeItem('token');
  delete axios.defaults.headers.common['Authorization'];
  router.push('/login');
}
</script>