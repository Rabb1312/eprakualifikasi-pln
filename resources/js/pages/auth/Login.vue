<template>
  <div class="login-bg">
    <button class="back-home" @click="goHome">
      <i class="fas fa-arrow-left"></i> Kembali ke Beranda
    </button>
    <div class="login-container">
      <!-- Side Info -->
      <div class="login-left">
        <div class="logo-section">
          <div class="logo">
            <img src="/assets/footer_logo.png" alt="Logo PLN" />
          </div>
        </div>
        <div class="welcome-text">
          <h1>Selamat Datang</h1>
          <p style="font-style: italic;">Become a Trusted EPC Energy Company</p>
        </div>
        <div class="features-list">
          <div class="feature-item">
            <i class="fas fa-shield-alt"></i>
            <span>Keamanan Data Terjamin</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-clock"></i>
            <span>Proses Verifikasi Cepat</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-file"></i>
            <span>Dokumen Digital Terintegrasi</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-chart-line"></i>
            <span>Tracking Status Real-time</span>
          </div>
        </div>
      </div>
      
      <!-- Login Form -->
      <div class="login-right">
        <div class="login-header">
          <h2>Login</h2>
          <p>{{ role === 'admin' ? 'Masuk sebagai Admin untuk kelola sistem' : 'Masuk ke akun Anda untuk melanjutkan' }}</p>
        </div>
        <div class="role-tabs">
          <button class="role-tab" :class="{ active: role === 'vendor' }" @click="role = 'vendor'">Vendor</button>
          <button class="role-tab" :class="{ active: role === 'admin' }" @click="role = 'admin'">Admin</button>
        </div>
        <form class="login-form" @submit.prevent="login">
          <div v-if="alertMsg" :class="['alert', alertTypeClass]" v-html="alertMsg"/>
          <div class="form-group">
            <label for="email">Username atau Email</label>
            <div class="input-wrapper">
              <i class="fas fa-user input-icon"></i>
              <input v-model="email" id="email" type="text" required placeholder="Masukkan Email/Username" />
            </div>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
              <i class="fas fa-lock input-icon"></i>
              <input :type="showPassword ? 'text' : 'password'" v-model="password" id="password" placeholder="Password" required />
              <i :class="['fas', showPassword ? 'fa-eye-slash' : 'fa-eye', 'password-toggle']" @click="showPassword = !showPassword"></i>
            </div>
          </div>
          <div class="remember-forgot">
            <label class="remember-me">
              <input type="checkbox" v-model="remember" />
              <span>Ingat saya</span>
            </label>
            <router-link class="forgot-password" to="/forgot-password">Lupa password?</router-link>
          </div>
          <button class="login-btn" :disabled="loading">
            <span v-if="!loading">Masuk</span>
            <span v-else><i class="fas fa-spinner fa-spin"></i> Loading...</span>
          </button>
        </form>
        <div class="register-link">
          <p>
            Belum punya akun? <router-link to="/registration">Daftar sebagai vendor</router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const email = ref('')
const password = ref('')
const remember = ref(false)
const showPassword = ref(false)
const alertMsg = ref('')
const alertType = ref('error') // 'error', 'success', 'info', 'warning'
const loading = ref(false)
const role = ref('vendor')
const router = useRouter()

const alertTypeClass = computed(() => {
  return {
    error: 'alert-error',
    success: 'alert-success',
    info: 'alert-info',
    warning: 'alert-warning'
  }[alertType.value]
})

function showAlert(type, msg) {
  alertType.value = type
  alertMsg.value = msg
  setTimeout(() => { alertMsg.value = '' }, 8000)
}

function goHome() {
  router.push('/')
}

async function login() {
  loading.value = true
  alertMsg.value = ''
  try {
    // Ganti URL API login sesuai backend Anda
    const res = await axios.post('/api/login', {
      email: email.value,
      password: password.value,
      userType: role.value,
      remember: remember.value
    })
    // Simpan token, redirect, dll
    localStorage.setItem('token', res.data.token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${res.data.token}`
    showAlert('success', 'Login berhasil, mengalihkan...')
    setTimeout(() => {
      router.push('/')
    }, 1200)
  } catch (e) {
    showAlert('error', e.response?.data?.message || 'Login gagal')
  }
  loading.value = false
}
</script>

<style scoped>
.login-bg {
  min-height: 100vh;
  background: linear-gradient(rgba(30, 60, 114, 0.6), rgba(30, 60, 114, 0.6)), url('/assets/pln1.jpg');
  background-size: cover;
  background-position: center;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  padding: 20px;
  font-family: 'Montserrat', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.back-home {
  position: absolute;
  top: 20px;
  left: 20px;
  background: rgba(255,255,255,0.2);
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 25px;
  cursor: pointer;
  font-weight: 600;
  z-index: 3;
  transition: all 0.3s;
}
.back-home:hover {
  background: rgba(255,255,255,0.3);
}

.login-container {
  background: rgba(255,255,255,0.95);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  box-shadow: 0 25px 50px rgba(0,0,0,0.2);
  overflow: hidden;
  width: 100%;
  max-width: 1000px;
  min-height: 600px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  position: relative;
  z-index: 2;
}

.login-left {
  background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
  color: white;
  padding: 60px 40px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  position: relative;
}

.logo-section {
  position: relative;
  z-index: 1;
}
.logo {
  width: 300px;
  border-radius: 15px;
  overflow: hidden;
  margin: 0 auto 20px;
  background: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
}
.logo img {
  width: 300px;           
  max-width: 100%; 
  object-fit: cover;
}
.welcome-text h1 {
  font-size: 2.2em;
  margin-bottom: 10px;
  font-weight: 700;
}
.features-list {
  margin-top: 30px;
  text-align: left;
}
.feature-item {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
  font-size: 1em;
}
.feature-item i {
  margin-right: 10px;
  color: #ffd700;
  font-size: 1.2em;
}

.login-right {
  padding: 60px 40px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.login-header {
  text-align: center;
  margin-bottom: 40px;
}
.login-header h2 {
  color: #1e3c72;
  font-size: 2em;
  margin-bottom: 10px;
}
.login-header p {
  color: #666;
  font-size: 1.1em;
}

.role-tabs {
  display: flex;
  margin-bottom: 30px;
  border-radius: 10px;
  background: #f8f9fa;
  padding: 5px;
}
.role-tab {
  flex: 1;
  padding: 12px 20px;
  background: none;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  color: #666;
  transition: all 0.3s;
}
.role-tab.active {
  background: #1e3c72;
  color: white;
  box-shadow: 0 5px 15px rgba(30,60,114,0.3);
}

.login-form {
  margin-bottom: 30px;
}
.form-group {
  margin-bottom: 25px;
  position: relative;
}
.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #1e3c72;
}
.input-wrapper {
  position: relative;
}
.form-group input {
  width: 100%;
  padding: 15px 20px 15px 50px;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  font-size: 16px;
  background: #f8f9fa;
  transition: all 0.3s;
}
.form-group input:focus {
  border-color: #1e3c72;
  background: white;
  box-shadow: 0 0 0 3px rgba(30,60,114,0.1);
  outline: none;
}
.input-icon {
  position: absolute;
  left: 18px;
  top: 50%;
  transform: translateY(-50%);
  color: #666;
  font-size: 1.1em;
}
.password-toggle {
  position: absolute;
  right: 18px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  color: #666;
  font-size: 1.1em;
}

.remember-forgot {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}
.remember-me {
  display: flex;
  align-items: center;
  gap: 8px;
}
.remember-me input[type="checkbox"] {
  width: auto;
}
.forgot-password {
  color: #1e3c72;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s;
}
.forgot-password:hover {
  color: #ffd700;
}

.login-btn {
  width: 100%;
  padding: 15px;
  background: linear-gradient(135deg,#1e3c72 0%,#2a5298 100%);
  color: white;
  border: none;
  border-radius: 12px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
  margin-bottom: 20px;
  position: relative;
}
.login-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
.login-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(30,60,114,0.3);
}

.register-link {
  text-align: center;
  color: #666;
}
.register-link a {
  color: #1e3c72;
  text-decoration: none;
  font-weight: 600;
}
.register-link a:hover {
  color: #ffd700;
}

.alert {
  padding: 12px 16px;
  margin-bottom: 20px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  display: block;
}
.alert-error {
  background-color: #f8d7da;
  border: 1px solid #f5c6cb;
  color: #721c24;
}
.alert-success {
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
  color: #155724;
}
.alert-info {
  background-color: #cce7ff;
  border: 1px solid #b8daff;
  color: #004085;
}
.alert-warning {
  background-color: #fff3cd;
  border: 1px solid #ffeaa7;
  color: #856404;
}

@media (max-width: 768px) {
  .login-container {
    grid-template-columns: 1fr;
    max-width: 400px;
  }
  .login-left {
    display: none;
  }
  .login-right {
    padding: 40px 30px;
  }
  .role-tabs {
    flex-direction: column;
    gap: 5px;
  }
  .login-bg {
    padding: 0;
  }
}
</style>