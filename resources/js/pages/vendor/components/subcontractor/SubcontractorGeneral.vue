<!-- resources/js/views/components/subcontractor/SubcontractorGeneral.vue -->
<template>
  <div class="subcontractor-general">
    <div class="tab-header">
      <h3>
        <i class="fas fa-info-circle"></i>
        General Information
      </h3>
      <p>Basic information about your company from vendor registration.</p>
    </div>

    <div class="form-grid">
      <!-- Row 1 -->
      <div class="form-row">
        <div class="form-group">
          <label>Nomor Vendor</label>
          <input 
            type="text" 
            :value="vendor.nomor_vendor" 
            readonly 
            class="readonly-input"
          />
        </div>
        <div class="form-group">
          <label>Tipe Perusahaan</label>
          <input 
            type="text" 
            value="Subcontractor" 
            readonly 
            class="readonly-input"
          />
        </div>
      </div>

      <!-- Row 2 -->
      <div class="form-row">
        <div class="form-group full-width">
          <label>Nama Perusahaan</label>
          <input 
            type="text" 
            :value="vendor.nama_perusahaan" 
            readonly 
            class="readonly-input"
          />
        </div>
      </div>

      <!-- Row 3 -->
      <div class="form-row">
        <div class="form-group">
          <label>NPWP</label>
          <input 
            v-model="formData.npwp"
            type="text" 
            placeholder="00.000.000.0-000.000"
            pattern="\\d{2}\\.\\d{3}\\.\\d{3}\\.\\d{1}-\\d{3}\\.\\d{3}"
          />
        </div>
        <div class="form-group">
          <label>Tanggal Berdiri</label>
          <input 
            v-model="formData.tanggal_berdiri"
            type="date"
            required
          />
        </div>
      </div>

      <!-- Row 4 -->
      <div class="form-row">
        <div class="form-group">
          <label>Tanggal Mulai Beroperasi</label>
          <input 
            v-model="formData.tanggal_beroperasi"
            type="date"
            required
          />
        </div>
        <div class="form-group">
          <label>Nomor Telepon</label>
          <input 
            v-model="formData.phone"
            type="tel"
            placeholder="+62 xxx xxxx xxxx"
            required
          />
        </div>
      </div>

      <!-- Row 5 -->
      <div class="form-row">
        <div class="form-group full-width">
          <label>Alamat</label>
          <textarea 
            v-model="formData.alamat"
            rows="3"
            placeholder="Alamat lengkap perusahaan"
            required
          ></textarea>
        </div>
      </div>

      <!-- Row 6 -->
      <div class="form-row">
        <div class="form-group">
          <label>Kode Pos</label>
          <input 
            v-model="formData.kode_pos"
            type="text"
            maxlength="5"
            placeholder="12345"
          />
        </div>
        <div class="form-group">
          <label>Website</label>
          <input 
            v-model="formData.website"
            type="url"
            placeholder="https://www.example.com"
          />
        </div>
      </div>

      <!-- Row 7 -->
      <div class="form-row">
        <div class="form-group full-width">
          <label>Alamat Kantor Operasional</label>
          <textarea 
            v-model="formData.alamat_kantor_operasional"
            rows="3"
            placeholder="Alamat kantor operasional (jika berbeda dengan alamat utama)"
          ></textarea>
        </div>
      </div>

      <!-- Source Information (Readonly) -->
      <div class="form-row">
        <div class="form-group">
          <label>Sumber Informasi</label>
          <input 
            :value="getSourceLabel(vendor.sumber_informasi)" 
            readonly 
            class="readonly-input"
          />
        </div>
        <div class="form-group" v-if="vendor.rekomendasi_dari">
          <label>Rekomendasi Dari</label>
          <input 
            :value="vendor.rekomendasi_dari" 
            readonly 
            class="readonly-input"
          />
        </div>
      </div>
    </div>

    <div class="form-actions">
      <button @click="saveGeneral" :disabled="loading" class="btn btn-primary">
        <i class="fas fa-save"></i>
        {{ loading ? 'Saving...' : 'Save General Information' }}
      </button>
    </div>

    <!-- Alert -->
    <AlertComponent 
      v-if="alertMsg"
      :type="alertType"
      :message="alertMsg"
      @close="alertMsg = ''"
    />
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue'
import axios from 'axios'
import AlertComponent from '../AlertComponent.vue'

const props = defineProps({
  vendor: Object
})

const emit = defineEmits(['updated'])

const loading = ref(false)
const alertMsg = ref('')
const alertType = ref('success')

const formData = reactive({
  npwp: '',
  tanggal_berdiri: '',
  tanggal_beroperasi: '',
  phone: '',
  alamat: '',
  kode_pos: '',
  website: '',
  alamat_kantor_operasional: ''
})

watch(() => props.vendor, (newVendor) => {
  if (newVendor) {
    initializeFormData()
  }
}, { immediate: true, deep: true })

function initializeFormData() {
  if (!props.vendor) return
  
  formData.npwp = props.vendor.npwp || ''
  formData.tanggal_berdiri = props.vendor.tanggal_berdiri || ''
  formData.tanggal_beroperasi = props.vendor.tanggal_beroperasi || ''
  formData.phone = props.vendor.phone || ''
  formData.alamat = props.vendor.alamat || ''
  formData.kode_pos = props.vendor.kode_pos || ''
  formData.website = props.vendor.website || ''
  formData.alamat_kantor_operasional = props.vendor.alamat_kantor_operasional || ''
}

function getSourceLabel(source) {
  const sources = {
    'website': 'Website',
    'instagram': 'Instagram', 
    'facebook': 'Facebook',
    'rekomendasi': 'Rekomendasi',
    'brosur': 'Brosur',
    'lainnya': 'Lainnya'
  }
  return sources[source] || source
}

async function saveGeneral() {
  loading.value = true
  
  try {
    const token = localStorage.getItem('token')
    const response = await axios.put('/api/vendor/profile', formData, {
      headers: { Authorization: `Bearer ${token}` }
    })

    if (response.data.success) {
      showAlert('success', 'General information saved successfully!')
      emit('updated', response.data.data.vendor)
    } else {
      showAlert('error', response.data.message || 'Failed to save general information')
    }
  } catch (error) {
    console.error('Save general error:', error)
    showAlert('error', error.response?.data?.message || 'Failed to save general information')
  } finally {
    loading.value = false
  }
}

function showAlert(type, message) {
  alertType.value = type
  alertMsg.value = message
  setTimeout(() => {
    alertMsg.value = ''
  }, 5000)
}
</script>

<style scoped>
.subcontractor-general {
  max-width: 800px;
}

.tab-header {
  margin-bottom: 2rem;
}

.tab-header h3 {
  color: #212529;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.tab-header p {
  color: #6c757d;
  margin: 0;
}

.form-grid {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-group label {
  font-weight: 500;
  color: #495057;
  margin-bottom: 0.5rem;
}

.form-group input,
.form-group textarea {
  padding: 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 6px;
  font-size: 0.875rem;
  transition: border-color 0.2s;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #fd7e14;
  box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
}

.readonly-input {
  background: #f8f9fa !important;
  color: #6c757d !important;
  cursor: not-allowed;
}

.form-actions {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid #dee2e6;
}

.btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-primary {
  background: #fd7e14;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: #e56b00;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

@media (max-width: 768px) {
  .form-row {
    grid-template-columns: 1fr;
  }
}
</style>