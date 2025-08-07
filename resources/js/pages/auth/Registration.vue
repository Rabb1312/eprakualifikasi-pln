<template>
  <div class="register-bg">
    <button class="back-home" @click="goHome">
      <i class="fas fa-arrow-left"></i> Kembali ke Beranda
    </button>
    <div class="register-container">
      <!-- Left Side Info -->
      <div class="register-left">
        <div class="logo-section">
          <div class="logo">
            <img src="/assets/footer_logo.png" alt="Logo PLN" />
          </div>
        </div>
        <div class="welcome-text">
          <h1 style="font-style: italic;">Become a Trusted EPC Energy Company</h1>
        </div>
        <div class="features-list">
          <div class="feature-item">
            <i class="fas fa-user-plus"></i>
            <span>Registrasi Mudah &amp; Cepat</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-envelope"></i>
            <span>Verifikasi Email Otomatis</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-shield-alt"></i>
            <span>Keamanan Data Terjamin</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-handshake"></i>
            <span>Peluang Kerjasama</span>
          </div>
        </div>
      </div>
      <!-- Right Side Form -->
      <div class="register-right">
        <div class="register-header">
          <h2>Form Registrasi</h2>
          <p>Lengkapi data perusahaan Anda untuk mendaftar sebagai vendor</p>
        </div>
        <div v-if="alertMsg" :class="['alert', alertTypeClass]" v-html="alertMsg" />

        <form class="register-form" @submit.prevent="register">
          <div class="form-group">
            <label for="companyName">Nama Perusahaan <span style="color: red;">*</span></label>
            <div class="input-wrapper">
              <i class="fas fa-building input-icon"></i>
              <input v-model="form.companyName" id="companyName" required placeholder="Nama Perusahaan" />
            </div>
          </div>

          <div class="form-group">
            <label for="npwp">NPWP Perusahaan</label>
            <div class="input-wrapper">
              <i class="fas fa-file-invoice input-icon"></i>
              <input
                v-model="form.npwp"
                id="npwp"
                class="npwp-format"
                maxlength="20"
                placeholder="00.000.000.0-000.000"
                @input="formatNPWP"
              />
            </div>
            <div class="npwp-helper">
              <i class="fas fa-info-circle" style="color: #17a2b8;" />
              <span>
                Format:
                <span class="npwp-example">00.000.000.0-000.000</span>
              </span>
            </div>
          </div>

          <div class="form-group">
            <label for="companyType">Tipe Perusahaan <span style="color: red;">*</span></label>
            <div class="input-wrapper">
              <i class="fas fa-industry input-icon"></i>
              <select v-model="form.companyType" id="companyType" required @change="updateCompanyDesc">
                <option value="">Pilih Tipe Perusahaan</option>
                <option value="distributor">Distributor</option>
                <option value="subcontractor">Subcontractor</option>
                <option value="manufacturer">Manufacturer/Fabrication</option>
                <option value="forwarder">Forwarder</option>
              </select>
            </div>
            <div
              class="company-description"
              v-if="companyDesc"
              style="margin-top:10px; padding:10px; background:#f8f9fa; border-radius:5px; font-size:13px;"
              v-html="companyDesc"
            />
          </div>

          <div class="form-group">
            <label for="emailAddress">Alamat Email <span style="color: red;">*</span></label>
            <div class="input-wrapper">
              <i class="fas fa-envelope input-icon"></i>
              <input
                v-model="form.emailAddress"
                id="emailAddress"
                type="email"
                required
                placeholder="email@perusahaan.com"
              />
            </div>
            <small style="color: #666; font-size: 12px;">Email akan digunakan untuk verifikasi akun</small>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="username">Username <span style="color: red;">*</span></label>
              <div class="input-wrapper">
                <i class="fas fa-user input-icon"></i>
                <input
                  v-model="form.username"
                  id="username"
                  required
                  placeholder="Username"
                  minlength="4"
                />
              </div>
              <small style="color: #666; font-size: 12px;">Minimal 4 karakter</small>
            </div>
            <div class="form-group">
              <label for="password">Password <span style="color: red;">*</span></label>
              <div class="input-wrapper">
                <i class="fas fa-lock input-icon"></i>
                <input
                  :type="showPassword ? 'text' : 'password'"
                  v-model="form.password"
                  id="password"
                  required
                  minlength="6"
                  placeholder="Password"
                />
                <i
                  :class="['fas', showPassword ? 'fa-eye-slash' : 'fa-eye', 'password-toggle']"
                  @click="showPassword = !showPassword"
                ></i>
              </div>
              <small style="color: #666; font-size: 12px;">Minimal 6 karakter</small>
            </div>
          </div>

          <div class="form-group">
            <label for="knownFrom">Mengetahui informasi PT PLN dari <span style="color: red;">*</span></label>
            <div class="input-wrapper">
              <i class="fas fa-info-circle input-icon"></i>
              <select v-model="form.knownFrom" id="knownFrom" required @change="onKnownFromChange">
                <option value="">Pilih Sumber Informasi</option>
                <option value="website">Website</option>
                <option value="instagram">Instagram</option>
                <option value="facebook">Facebook</option>
                <option value="other">Lainnya</option>
              </select>
            </div>
          </div>

          <div class="form-group" v-if="form.knownFrom === 'other'">
            <label for="other">Lainnya</label>
            <div class="input-wrapper">
              <i class="fas fa-comment input-icon"></i>
              <input v-model="form.other" id="other" placeholder="Sebutkan sumber informasi lainnya" required />
            </div>
          </div>

          <!-- Checkbox Persetujuan -->
          <div class="checkbox-group">
            <label class="checkbox-wrapper">
              <input type="checkbox" v-model="form.agreement" required />
              <span class="checkbox-text">
                Dengan ini saya menyatakan bahwa data dan informasi yang disampaikan pada halaman website
                e-prakualifikasi adalah benar dan bisa dipertanggungjawabkan.
              </span>
            </label>
          </div>

          <button type="submit" class="register-btn" :disabled="loading">
            <span v-if="!loading" class="btn-text">
              <i class="fas fa-user-plus"></i> Daftar Sekarang
            </span>
            <span v-else class="btn-text"><i class="fas fa-spinner fa-spin"></i> Loading...</span>
          </button>

          <button type="button" class="back-btn" @click="goBack">
            <i class="fas fa-arrow-left"></i> Kembali
          </button>
        </form>
        <div class="login-link">
          <p>
            Sudah punya akun?
            <router-link to="/login">Login di sini</router-link>
          </p>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div v-if="showModal" class="modal" @click.self="showModal = false">
      <div class="modal-content">
        <div class="modal-icon">
          <i class="fas fa-envelope-check"></i>
        </div>
        <h2>Registrasi Berhasil!</h2>
        <p v-if="!emailFailed">
          Email verifikasi telah dikirim ke alamat email Anda. Silakan cek kotak masuk dan spam folder, kemudian klik link verifikasi untuk mengaktifkan akun.
        </p>
        <p v-else>
          Registrasi berhasil! Namun email verifikasi gagal dikirim. Silakan hubungi admin untuk mengaktifkan akun Anda.
        </p>
        <div style="margin: 20px 0;">
          <strong>Langkah selanjutnya:</strong>
          <ol style="text-align: left; margin: 10px 0; padding-left: 20px;">
            <li>Buka email dan klik link verifikasi</li>
            <li>Login ke akun Anda</li>
            <li>Lengkapi dokumen yang diperlukan</li>
          </ol>
        </div>
        <router-link to="/login" class="modal-btn">
          <i class="fas fa-sign-in-alt"></i> Lanjut ke Login
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
const showPassword = ref(false)
const loading = ref(false)
const showModal = ref(false)
const emailFailed = ref(false)
const alertMsg = ref('')
const alertType = ref('error')

const form = ref({
  companyName: '',
  npwp: '',
  companyType: '',
  emailAddress: '',
  username: '',
  password: '',
  knownFrom: '',
  other: '',
  agreement: false,
})

const companyDesc = ref('')

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
function goBack() {
  router.push('/login')
}

function formatNPWP(e) {
  let value = e.target.value.replace(/\D/g, '').substring(0, 15)
  let formatted = ''
  if (value.length > 0) {
    formatted += value.substring(0, 2)
    if (value.length > 2) {
      formatted += '.' + value.substring(2, 5)
      if (value.length > 5) {
        formatted += '.' + value.substring(5, 8)
        if (value.length > 8) {
          formatted += '.' + value.substring(8, 9)
          if (value.length > 9) {
            formatted += '-' + value.substring(9, 12)
            if (value.length > 12) {
              formatted += '.' + value.substring(12, 15)
            }
          }
        }
      }
    }
  }
  form.value.npwp = formatted
}

function validateNPWP(npwp) {
  if (!npwp) return true
  const digits = npwp.replace(/\D/g, '')
  return digits.length === 15
}

function updateCompanyDesc() {
  const descs = {
    distributor: {
      icon: 'fas fa-truck',
      title: 'Distributor',
      text: 'Perusahaan yang bergerak dalam suplai material/equipment dari pabrikan',
    },
    subcontractor: {
      icon: 'fas fa-hard-hat',
      title: 'Subcontractor',
      text: 'Perusahaan yang memberikan pelayanan berupa jasa pekerjaan',
    },
    manufacturer: {
      icon: 'fas fa-industry',
      title: 'Manufacturer/Fabrication',
      text: 'Perusahaan yang bergerak dalam bidang fabrikasi atau suplai material / equipment',
    },
    forwarder: {
      icon: 'fas fa-shipping-fast',
      title: 'Forwarder',
      text: 'Perusahaan yang memberikan pelayanan berupa jasa pengiriman',
    },
  }
  const val = form.value.companyType
  if (val && descs[val]) {
    const d = descs[val]
    companyDesc.value = `<div style="display: flex; align-items: center; margin-bottom: 5px;">
      <i class="${d.icon}" style="margin-right: 8px; color: #1e3c72;"></i>
      <strong style="color: #1e3c72;">${d.title}</strong>
    </div>
    <p style="color: #666;">${d.text}</p>`
  } else {
    companyDesc.value = ''
  }
}

function onKnownFromChange() {
  if (form.value.knownFrom !== 'other') {
    form.value.other = ''
  }
}

function showAlert(type, msg) {
  alertType.value = type
  alertMsg.value = msg
  setTimeout(() => (alertMsg.value = ''), 6000)
}

async function register() {
  // Validasi manual
  if (!form.value.companyName) return showAlert('error', 'Nama perusahaan wajib diisi!')
  if (!form.value.companyType) return showAlert('error', 'Tipe perusahaan wajib dipilih!')
  if (!form.value.emailAddress) return showAlert('error', 'Email wajib diisi!')
  if (!form.value.username || form.value.username.length < 4)
    return showAlert('error', 'Username minimal 4 karakter!')
  if (!form.value.password || form.value.password.length < 6)
    return showAlert('error', 'Password minimal 6 karakter!')
  if (!form.value.knownFrom) return showAlert('error', 'Sumber informasi wajib dipilih!')
  if (form.value.knownFrom === 'other' && !form.value.other)
    return showAlert('error', 'Mohon isi sumber informasi lainnya!')
  if (!form.value.agreement)
    return showAlert('error', 'Anda harus menyetujui pernyataan data dan informasi.')
  if (form.value.npwp && !validateNPWP(form.value.npwp))
    return showAlert('error', 'Format NPWP tidak valid. NPWP harus terdiri dari 15 digit.')

  loading.value = true

  try {
    // Ganti endpoint sesuai backend Anda
    const res = await axios.post('/api/register', form.value)
    // result.success, result.data.email_sent
    if (res.data.success) {
      emailFailed.value = !(res.data.data && res.data.data.email_sent)
      showModal.value = true
      Object.keys(form.value).forEach((k) => (form.value[k] = ''))
      form.value.agreement = false
      companyDesc.value = ''
    } else {
      showAlert('error', res.data.message)
    }
  } catch (e) {
    showAlert('error', e.response?.data?.message || 'Terjadi kesalahan jaringan. Silakan coba lagi.')
  }
  loading.value = false
}
</script>

<style scoped>
/* Styles mirip registrasi.php - bisa modifikasi lebih lanjut */
.register-bg {
  min-height: 100vh;
  background: linear-gradient(rgba(30, 60, 114, 0.6), rgba(30, 60, 114, 0.6)),
    url('/assets/pln1.jpg');
  background-size: cover;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
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
.register-container {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
  overflow: hidden;
  width: 100%;
  max-width: 1200px;
  min-height: 700px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  position: relative;
  z-index: 2;
}
.register-left {
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
.register-right {
  padding: 40px;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  overflow-y: auto;
  max-height: 100vh;
}
.register-header {
  text-align: center;
  margin-bottom: 30px;
}
.register-header h2 {
  color: #1e3c72;
  font-size: 2em;
  margin-bottom: 10px;
}
.register-header p {
  color: #666;
  font-size: 1.1em;
}
.register-form {
  margin-bottom: 20px;
}
.form-group {
  margin-bottom: 20px;
  position: relative;
}
.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #1e3c72;
  font-size: 14px;
}
.input-wrapper {
  position: relative;
}
.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 12px 15px 12px 45px;
  border: 2px solid #e9ecef;
  border-radius: 10px;
  font-size: 14px;
  transition: all 0.3s ease;
  background: #f8f9fa;
  font-family: inherit;
}
.form-group textarea {
  resize: vertical;
  min-height: 80px;
  line-height: 1.5;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #1e3c72;
  background: white;
  box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.1);
  outline: none;
}
.input-icon {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: #666;
  font-size: 1em;
}
.textarea-icon {
  position: absolute;
  left: 15px;
  top: 15px;
  color: #666;
  font-size: 1em;
}
.password-toggle {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  color: #666;
  font-size: 1em;
}
.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}
.npwp-format {
  font-family: 'Courier New', monospace;
  letter-spacing: 1px;
}
.npwp-helper {
  font-size: 12px;
  color: #666;
  margin-top: 5px;
  display: flex;
  align-items: center;
  gap: 5px;
}
.npwp-example {
  background: #e3f2fd;
  padding: 2px 6px;
  border-radius: 3px;
  font-family: 'Courier New', monospace;
  font-size: 11px;
}
.checkbox-group {
  margin-bottom: 25px;
  padding: 20px;
  background: #f8f9fa;
  border-radius: 10px;
  border: 2px solid #e9ecef;
}
.checkbox-wrapper {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  cursor: pointer;
}
.checkbox-wrapper input[type='checkbox'] {
  width: 20px;
  height: 20px;
  margin: 0;
  padding: 0;
  cursor: pointer;
  accent-color: #1e3c72;
  flex-shrink: 0;
  margin-top: 2px;
}
.checkbox-text {
  font-size: 14px;
  line-height: 1.6;
  color: #333;
  text-align: justify;
}
.checkbox-wrapper:hover .checkbox-text {
  color: #1e3c72;
}
.register-btn {
  width: 100%;
  padding: 15px;
  background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
  color: white;
  border: none;
  border-radius: 12px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-bottom: 15px;
  position: relative;
}
.register-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(30, 60, 114, 0.3);
}
.register-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
.back-btn {
  width: 100%;
  padding: 15px;
  background: #6c757d;
  color: white;
  border: none;
  border-radius: 12px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-bottom: 20px;
}
.back-btn:hover {
  background: #5a6268;
  transform: translateY(-2px);
}
.login-link {
  text-align: center;
  color: #666;
}
.login-link a {
  color: #1e3c72;
  text-decoration: none;
  font-weight: 600;
}
.login-link a:hover {
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
.modal {
  display: flex;
  align-items: center;
  justify-content: center;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
}
.modal-content {
  background-color: #fefefe;
  margin: 10% auto;
  padding: 30px;
  border-radius: 15px;
  width: 90%;
  max-width: 500px;
  text-align: center;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}
.modal-icon {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
  font-size: 2.5em;
  color: white;
}
.modal-btn {
  background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
  color: white;
  padding: 12px 25px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  display: inline-block;
  transition: all 0.3s ease;
}
.modal-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(30, 60, 114, 0.3);
}
/* Responsive */
@media (max-width: 768px) {
  .register-container {
    grid-template-columns: 1fr;
    max-width: 500px;
  }
  .register-left {
    display: none;
  }
  .register-right {
    padding: 30px 25px;
  }
  .form-row {
    grid-template-columns: 1fr;
    gap: 15px;
  }
  .register-bg {
    padding: 0;
  }
}
</style>