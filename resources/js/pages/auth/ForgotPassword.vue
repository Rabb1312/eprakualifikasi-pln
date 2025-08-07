<template>
  <div class="forgot-bg">
    <button class="back-home" @click="goHome">
      <i class="fas fa-arrow-left"></i> Kembali ke Beranda
    </button>
    
    <div class="forgot-container">
      <div class="forgot-header">
        <div class="icon-container">
          <i class="fas fa-key"></i>
        </div>
        <h2>Lupa Password</h2>
        <p>Masukkan alamat email Anda untuk menerima link reset password</p>
      </div>
      
      <div v-if="alertMsg" :class="['alert', alertTypeClass]" v-html="alertMsg" />
      
      <form v-if="!emailSent" class="forgot-form" @submit.prevent="sendResetLink">
        <div class="form-group">
          <label for="email">Alamat Email</label>
          <div class="input-wrapper">
            <i class="fas fa-envelope input-icon"></i>
            <input
              v-model="email"
              id="email"
              type="email"
              required
              placeholder="Masukkan alamat email Anda"
            />
          </div>
        </div>
        
        <button type="submit" class="forgot-btn" :disabled="loading">
          <span v-if="!loading">
            <i class="fas fa-paper-plane"></i> Kirim Link Reset
          </span>
          <span v-else>
            <i class="fas fa-spinner fa-spin"></i> Mengirim...
          </span>
        </button>
      </form>
      
      <div v-else class="success-message">
        <div class="success-icon">
          <i class="fas fa-envelope-check"></i>
        </div>
        <h3>Email Berhasil Dikirim!</h3>
        <p>
          Kami telah mengirim link reset password ke alamat email <strong>{{ email }}</strong>.
          Silakan cek kotak masuk dan spam folder Anda.
        </p>
        <div class="info-box">
          <h4>Langkah selanjutnya:</h4>
          <ol>
            <li>Buka email dari PLN E-Prakualifikasi</li>
            <li>Klik link "Reset Password"</li>
            <li>Masukkan password baru Anda</li>
            <li>Login dengan password baru</li>
          </ol>
        </div>
        <button @click="resetForm" class="link-btn">
          <i class="fas fa-redo"></i> Kirim ke Email Lain
        </button>
      </div>
      
      <div class="back-links">
        <router-link to="/login" class="link-btn">
          <i class="fas fa-arrow-left"></i> Kembali ke Login
        </router-link>
        <span class="separator">|</span>
        <router-link to="/registration" class="link-btn">
          <i class="fas fa-user-plus"></i> Daftar Akun Baru
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const email = ref('')
const loading = ref(false)
const emailSent = ref(false)
const alertMsg = ref('')
const alertType = ref('error')

axios.defaults.baseURL = "http://eprakualifikasi-pln.test"

const alertTypeClass = computed(() => {
  return {
    error: 'alert-error',
    success: 'alert-success',
    info: 'alert-info',
  }[alertType.value]
})

function goHome() {
  router.push('/')
}

function showAlert(type, msg) {
  alertType.value = type
  alertMsg.value = msg
  setTimeout(() => (alertMsg.value = ''), 8000)
}

function resetForm() {
  emailSent.value = false
  email.value = ''
  alertMsg.value = ''
}

async function sendResetLink() {
  if (!email.value.trim()) {
    showAlert('error', 'Alamat email wajib diisi!')
    return
  }
  
  loading.value = true
  alertMsg.value = ''
  
  try {
    const response = await axios.post('/api/auth/forgot-password', {
      email: email.value.trim()
    })
    
    if (response.data.success) {
      emailSent.value = true
      showAlert('success', 'Link reset password berhasil dikirim ke email Anda!')
    } else {
      showAlert('error', response.data.message || 'Gagal mengirim link reset password')
    }
  } catch (error) {
    console.error('Forgot password error:', error)
    
    if (error.response) {
      const errorData = error.response.data
      
      if (error.response.status === 422) {
        showAlert('error', 'Email tidak valid atau tidak terdaftar dalam sistem')
      } else if (errorData.requires_verification) {
        showAlert('error', 
          errorData.message + 
          '<br><small>Silakan verifikasi email terlebih dahulu sebelum reset password.</small>'
        )
      } else {
        showAlert('error', errorData.message || 'Terjadi kesalahan saat mengirim email')
      }
    } else {
      showAlert('error', 'Tidak dapat terhubung ke server. Silakan coba lagi.')
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.forgot-bg {
  min-height: 100vh;
  background: linear-gradient(rgba(30, 60, 114, 0.6), rgba(30, 60, 114, 0.6)),
    url('/assets/pln1.jpg');
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
  background: rgba(255, 255, 255, 0.2);
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
  background: rgba(255, 255, 255, 0.3);
}

.forgot-container {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
  padding: 60px 40px;
  text-align: center;
  max-width: 500px;
  width: 100%;
  position: relative;
  z-index: 2;
}

.forgot-header {
  margin-bottom: 40px;
}

.icon-container {
  width: 80px;
  height: 80px;
  margin: 0 auto 20px;
  border-radius: 50%;
  background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.5em;
  color: white;
}

.forgot-header h2 {
  color: #1e3c72;
  font-size: 2em;
  margin-bottom: 10px;
}

.forgot-header p {
  color: #666;
  font-size: 1.1em;
}

.form-group {
  margin-bottom: 25px;
  text-align: left;
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
  box-sizing: border-box;
}

.form-group input:focus {
  border-color: #dc3545;
  background: white;
  box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
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

.forgot-btn {
  width: 100%;
  padding: 15px;
  background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
  color: white;
  border: none;
  border-radius: 12px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
  margin-bottom: 30px;
}

.forgot-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(220, 53, 69, 0.3);
}

.forgot-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.success-message {
  text-align: center;
}

.success-icon {
  width: 100px;
  height: 100px;
  margin: 0 auto 20px;
  border-radius: 50%;
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 3em;
  color: white;
}

.success-message h3 {
  color: #1e3c72;
  margin-bottom: 15px;
}

.success-message p {
  color: #666;
  margin-bottom: 25px;
  line-height: 1.6;
}

.info-box {
  background: #f8f9fa;
  border-radius: 10px;
  padding: 20px;
  margin: 25px 0;
  text-align: left;
}

.info-box h4 {
  color: #1e3c72;
  margin-bottom: 10px;
}

.info-box ol {
  color: #666;
  padding-left: 20px;
}

.info-box li {
  margin: 8px 0;
}

.back-links {
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px solid #e9ecef;
}

.link-btn {
  color: #1e3c72;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 14px;
}

.link-btn:hover {
  color: #ffd700;
}

.separator {
  margin: 0 15px;
  color: #ccc;
}

.alert {
  padding: 12px 16px;
  margin-bottom: 20px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
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

@media (max-width: 768px) {
  .forgot-container {
    padding: 40px 25px;
    margin: 10px;
  }
  
  .back-links {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  
  .separator {
    display: none;
  }
}
</style>